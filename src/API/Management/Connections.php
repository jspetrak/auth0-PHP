<?php
/**
 * Connections endpoints for the Management API.
 *
 * @package Auth0\SDK\API\Management
 */
namespace Auth0\SDK\API\Management;

/**
 * Class Connections.
 * Handles requests to the Connections endpoint of the v2 Management API.
 *
 * @package Auth0\SDK\API\Management
 */
class Connections extends GenericResource
{
    /**
     * Get all Connections by page.
     *
     * @param null|string $strategy - Connection strategy to retrieve.
     * @param null|string|array $fields - Fields to include or exclude from the result, empty to retrieve all fields.
     * @param null|boolean $include_fields - True to include $fields, false to exclude $fields.
     * @param null|string $name - Connection name to retrieve.
     * @param integer $page - Page number to get, zero-based.
     * @param null|integer $per_page - Number of results to get, null to return the default number.
     *
     * @return mixed|string
     *
     * @throws \Exception
     *
     * @link https://auth0.com/docs/api/management/v2#!/Connections/get_connections
     */
    public function getAll(
        $strategy = null,
        $fields = null,
        $include_fields = null,
        $name = null,
        $page = 0,
        $per_page = null
    ) {
        $request = $this->apiClient->method('get')->addPath('connections');

        if (null !== $strategy) {
            $request->withParam('strategy', $strategy);
        }

        if (!empty($fields)) {
            if (is_array($fields)) {
                $fields = implode(',', $fields);
            }
            $request->withParam('fields', $fields);
        }

        if (null !== $include_fields) {
            $request->withParam('include_fields', $include_fields);
        }

        if (null !== $name) {
            $request->withParam('name', $name);
        }

        $request->withParam('page', abs(intval($page)));

        if (null !== $per_page) {
            $request->withParam('per_page', $per_page);
        }

        return $request->call();
    }

    /**
     * Get a single Connection by ID.
     *
     * @param string $id - Connection ID to get.
     * @param null|string|array $fields - Fields to include or exclude from the result, empty to retrieve all fields.
     * @param null|boolean $include_fields - True to include $fields, false to exclude $fields.
     *
     * @return mixed|string
     *
     * @throws \Exception
     *
     * @link https://auth0.com/docs/api/management/v2#!/Connections/get_connections_by_id
     */
    public function get($id, $fields = null, $include_fields = null)
    {
        $request = $this->apiClient->method('get')->addPath('connections', $id);

        if (!empty($fields)) {
            if (is_array($fields)) {
                $fields = implode(',', $fields);
            }
            $request->withParam('fields', $fields);
        }

        if (null !== $include_fields) {
            $request->withParam('include_fields', $include_fields);
        }

        return $request->call();
    }

    /**
     * Delete a Connection by ID.
     *
     * @param string $id - Connection ID to delete.
     *
     * @return mixed|string
     *
     * @throws \Exception
     *
     * @link https://auth0.com/docs/api/management/v2#!/Connections/delete_connections_by_id
     */
    public function delete($id)
    {
        return $this->apiClient->method('delete')
            ->addPath('connections', $id)
            ->call();
    }

    /**
     * Delete a specific User for a Connection.
     *
     * @param string $id - Connection ID (currently only database connections are supported).
     * @param string $email - Email of the user to delete.
     *
     * @return mixed|string
     *
     * @throws \Exception
     *
     * @link https://auth0.com/docs/api/management/v2#!/Connections/delete_users_by_email
     */
    public function deleteUser($id, $email)
    {
        return $this->apiClient->method('delete')
            ->addPath('connections', $id)
            ->addPath('users')
            ->withParam('email', $email)
            ->call();
    }

    /**
     * Create a Connection.
     *
     * @param array $data - Connection create data; "name" and "strategy" fields are required.
     *
     * @return mixed|string
     *
     * @throws \Exception
     *
     * @link https://auth0.com/docs/api/management/v2#!/Connections/post_connections
     */
    public function create($data)
    {
        if (empty($data['name'])) {
            throw new \Exception('Missing required "name" field.');
        }

        if (empty($data['strategy'])) {
            throw new \Exception('Missing required "strategy" field.');
        }

        return $this->apiClient->method('post')
            ->addPath('connections')
            ->withBody(json_encode($data))
            ->call();
    }

    /**
     * Update a Connection.
     *
     * @param string $id - Connection ID to update.
     * @param array $data - Update data.
     *
     * @return mixed|string
     *
     * @throws \Exception
     *
     * @link https://auth0.com/docs/api/management/v2#!/Connections/patch_connections_by_id
     */
    public function update($id, $data)
    {
        return $this->apiClient->method('patch')
            ->addPath('connections', $id)
            ->withBody(json_encode($data))
            ->call();
    }
}
