<?php
namespace Auth0\Tests\API\Management;

use Auth0\SDK\API\Management;
use Auth0\Tests\API\BasicCrudTest;

/**
 * Class ConnectionsTest.
 *
 * @package Auth0\Tests\API\Management
 */
class ConnectionsTest extends BasicCrudTest
{
    /**
     * Unique identifier name for Connections.
     *
     * @var string
     */
    protected $id_name = 'id';

    /**
     * Name of the created Connection.
     * Appended with a random string in self::__construct().
     *
     * @var string
     */
    protected $create_connection_name = 'TEST-CREATE-CONNECTION-';

    /**
     * Strategy of the created Connection.
     *
     * @var string
     */
    protected $create_connection_strategy = 'auth0';

    /**
     * Should the Connection require a username when created?
     *
     * @var boolean
     */
    protected $create_connection_username_req = true;

    /**
     * Password policy of the created Connection.
     *
     * @var string
     */
    protected $create_connection_pw_policy = 'fair';

    /**
     * Should the Connection require a username when updated?
     *
     * @var boolean
     */
    protected $update_connection_username_req = true;

    /**
     * Password policy of the updated Connection.
     *
     * @var string
     */
    protected $update_connection_pw_policy = 'good';

    /**
     * ConnectionsTest constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->create_connection_name .= rand();
    }

    /**
     * Return the Connections API to test.
     *
     * @return Management\Connections
     */
    protected function getApiClient()
    {
        $token = $this->getToken($this->env, [
            'connections' => ['actions' => ['create', 'read', 'delete', 'update']],
            'users' => ['actions' => ['delete']],
        ]);
        $api = new Management($token, $this->domain);
        return $api->connections;
    }

    /**
     * Get the Connection create data to send with the test create call.
     *
     * @return array
     */
    protected function getCreateBody()
    {
        return [
            'name' => $this->create_connection_name,
            'strategy' => $this->create_connection_strategy,
            'options' => [
                'requires_username' => $this->create_connection_username_req,
                'passwordPolicy' => $this->create_connection_pw_policy,
            ],
        ];
    }

    /**
     * Tests the \Auth0\SDK\API\Management\Connections::getAll() method.
     *
     * @param Management\Connections $client - API client to use.
     * @param array $created_entity - Entity created during create() test.
     *
     * @return mixed
     *
     * @throws \Exception
     */
    protected function getAll($client, $created_entity)
    {
        $fields = array_keys($this->getCreateBody());
        $fields[] = $this->id_name;

        // Check that pagination and field inclusion works.
        $all_results = $client->getAll(null, $fields, true, null, 1, 1);
        $this->assertEquals(1, count($all_results));
        foreach ($fields as $field) {
            $this->assertArrayHasKey($field, $all_results[0]);
        }

        // If we want to check for the created result, we need all Connections.
        if ($this->findCreatedItem) {
            $all_results = $client->getAll(null, $fields, true, null, 0, 100);
        }

        return $all_results;
    }

    /**
     * Check that the Connection created matches the initial values sent.
     *
     * @param array $entity - The created Connection to check against initial values.
     */
    protected function afterCreate($entity)
    {
        $this->assertNotEmpty($entity[$this->id_name]);
        $this->assertEquals($this->create_connection_name, $entity['name']);
        $this->assertEquals($this->create_connection_strategy, $entity['strategy']);
        $this->assertEquals($this->create_connection_username_req, $entity['options']['requires_username']);
        $this->assertEquals($this->create_connection_pw_policy, $entity['options']['passwordPolicy']);
    }

    /**
     * Get the Connection update data to send with the test update call.
     *
     * @return array
     */
    protected function getUpdateBody()
    {
        return [
            'options' => [
                'requires_username' => $this->update_connection_username_req,
                'passwordPolicy' => $this->update_connection_pw_policy,
            ],
        ];
    }

    /**
     * Update entity returned values check.
     *
     * @param array $entity - Connection that was updated.
     */
    protected function afterUpdate($entity)
    {
        $this->assertEquals($this->update_connection_username_req, $entity['options']['requires_username']);
        $this->assertEquals($this->update_connection_pw_policy, $entity['options']['passwordPolicy']);
    }
}
