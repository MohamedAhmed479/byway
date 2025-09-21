<?php

namespace App\Filters;

use App\Traits\Tokenable;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class TokenAuthFilter implements FilterInterface
{
    use Tokenable;

    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return RequestInterface|ResponseInterface|string|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $response = service('response');

        $authHeader = $request->getHeaderLine('Authorization');

        if (!$authHeader) {
            $token = $request->getGet('token');
            if ($token) {
                $authHeader = $token;
            }
        }

        if (!$authHeader) {
            return $response->setJSON([
                'status' => false,
                'message' => 'Authorization token required',
                'error' => 'missing_token'
            ])->setStatusCode(401);
        }

        $token = $this->extractTokenFromHeader($authHeader);

        if (!$token) {
            return $response->setJSON([
                'status' => false,
                'message' => 'Invalid token format',
                'error' => 'invalid_token_format'
            ])->setStatusCode(401);
        }

        try {
            $result = $this->validateToken($token);

            if (!$result || !isset($result['user']) || !isset($result['token'])) {
                log_message('debug', 'Token validation failed - result: ' . json_encode($result));
                return $response->setJSON([
                    'status' => false,
                    'message' => 'Invalid or expired token',
                    'error' => 'invalid_token'
                ])->setStatusCode(401);
            }
            

            // Check if specific ability is required
            if (!empty($arguments)) {
                $requiredAbility = $arguments[0];
                if (!$this->hasAbility($result['token'], $requiredAbility)) {
                    return $response->setJSON([
                        'status' => false,
                        'message' => 'Insufficient permissions',
                        'error' => 'insufficient_permissions',
                        'required_ability' => $requiredAbility
                    ])->setStatusCode(403);
                }
            }

            // Store user and token data in request for later use
            $request->user  = $result['user'];
            $request->token = $result['token'];

        } catch (\Exception $e) {
            log_message('error', 'Token validation error: ' . $e->getMessage());

            return $response->setJSON([
                'status' => false,
                'message' => 'Token validation failed',
                'error' => 'validation_failed'
            ])->setStatusCode(401);
        }

        return $request;
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return ResponseInterface|void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
