<?php
/**
 * Users endpoints for the Management API.
 *
 * @package Auth0\SDK\API\Management
 */
namespace Auth0\SDK\API\Management;

/**
 * Class Users.
 * Handles requests to the Users endpoint of the v2 Management API.
 *
 * @package Auth0\SDK\API\Management
 */
class Users extends GenericResource
{
    /**
     * Get a single User by ID.
     *
     * @param string $user_id - User ID to get.
     *
     * @return mixed|string
     *
     * @throws \Exception
     */
    public function get($user_id)
    {
        return $this->apiClient->method('get')
            ->addPath('users', $user_id)
            ->call();
    }

    /**
     * Update a User.
     *
     * @param string $user_id - User ID to update.
     * @param array $data - User data to update.
     *
     * @return mixed|string
     *
     * @throws \Exception
     */
    public function update($user_id, $data)
    {
        return $this->apiClient->method('patch')
            ->addPath('users', $user_id)
            ->withBody(json_encode($data))
            ->call();
    }

    /**
     * Create a new User.
     *
     * @param array $data - User create data.
     *
     * @return mixed|string
     *
     * @throws \Exception
     */
    public function create($data)
    {
        // TODO: Enforce connection parameter
        // TODO: Enforce "phone_number" if "sms" connection, "email" if not

        return $this->apiClient->method('post')
            ->addPath('users')
            ->withBody(json_encode($data))
            ->call();
    }

    /**
     * Get all Users by page.
     * Wrapper for self::search().
     *
     * @param array $params - Search parameters to send.
     *
     * @return mixed|string
     *
     * @throws \Exception
     */
    public function getAll($params = [])
    {
        return $this->search($params);
    }

    /**
     * Search all Users.
     *
     * @param array $params - Search parameters to send.
     * @param integer           $page           - Page number to get, zero-based.
     * @param null|integer      $per_page       - Number of results to get, null to return the default number.
     *
     * @return mixed|string
     * @throws \Exception
     */
    public function search($params = [], $page = 0, $per_page = null)
    {
        if (! isset($params['page'])) {
            $params['page'] = $page;
        }

        if (! isset($params['per_page'])) {
            $params['per_page'] = $per_page;
        }

        return $this->apiClient->method('get')
            ->addPath('users')
            ->withParams($params)
            ->call();
    }

    /**
     * Delete a User by ID.
     *
     * @param string $user_id - User ID to delete.
     *
     * @return mixed|string
     *
     * @throws \Exception
     */
    public function delete($user_id)
    {
        return $this->apiClient->method('delete')
            ->addPath('users', $user_id)
            ->call();
    }

    /**
     * Link one user account to another.
     *
     * @param string $user_id - User ID of the primary identity where you are linking the secondary account to.
     * @param array $data - Secondary account to link; either link_with JWT or provider, connection_id, and user_id.
     *
     * @return array - Array of the primary account identities.
     *
     * @throws \Exception
     */
    public function linkAccount($user_id, $data)
    {
        return $this->apiClient->method('post')
            ->addPath('users', $user_id)
            ->addPath('identities')
            ->withBody(json_encode($data))
            ->call();
    }

    /**
     * Unlink an identity from the target user.
     *
     * @param string $user_id - User ID to unlink.
     * @param string $provider - Identity provider of the secondary linked account.
     * @param string $identity_id- The unique identifier of the secondary linked account.
     *
     * @return mixed|string
     *
     * @throws \Exception
     */
    public function unlinkAccount($user_id, $provider, $identity_id)
    {
        return $this->apiClient->method('delete')
            ->addPath('users', $user_id)
            ->addPath('identities', $provider)
            ->addPathVariable($identity_id)
            ->call();
    }

    /**
     * TODO: Does this endpoint exist anymore?
     *
     * @param string $user_id - User ID to unlink.
     * @param string $device_id - Device ID to unlink.
     *
     * @return mixed|string
     *
     * @throws \Exception
     */
    public function unlinkDevice($user_id, $device_id)
    {
        return $this->apiClient->method('delete')
            ->addPath('users', $user_id)
            ->addPath('devices', $device_id)
            ->call();
    }

    /**
     * @param string $user_id - User ID with the multifactor provider to delete.
     * @param string $mfa_provider - Multifactor provider to delete
     *
     * @return mixed|string
     *
     * @throws \Exception
     */
    public function deleteMultifactorProvider($user_id, $mfa_provider)
    {
        return $this->apiClient->method('delete')
            ->addPath('users', $user_id)
            ->addPath('multifactor', $mfa_provider)
            ->call();
    }
}
