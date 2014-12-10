<?php
namespace Architect\ORM\src;

use \Doctrine\Common\Collections\ArrayCollection;

/**
 * Architect\ORM\src\App
 *
 * API access app
 *
 * @category ORM
 * @package Architect
 * @subpackage ORM
 * @author Rob Lowcock <rob.lowcock@gmail.com>
 * @Entity
 * @Table(name="app")
 */
class App
{
    /**
     * The ID of the app
     * @var int
     * @Id
     * @Column(type="integer", name="app_id")
     * @GeneratedValue
     */
    protected $appId;

    /**
     * The name of the app
     * @var string
     * @Column(type="string", name="app_name")
     */
    protected $appName;

    /**
     * The URL of the app
     * @var string
     * @Column(type="string", nullable=true, name="app_url")
     */
    protected $appUrl;

    /**
     * The app secret
     * @var string
     * @Column(type="string", name="app_secret")
     */
    protected $appSecret;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tasks = new ArrayCollection;
    }

    /**
     * Get the ID of the app
     * @return int
     */
    public function getId()
    {
        return $this->appId;
    }

    /**
     * Get the name of the app
     * @return string
     */
    public function getAppName()
    {
        return $this->appName;
    }

    /**
     * Set the name of the app
     * @param string $appName
     */
    public function setAppName($appName)
    {
        $this->appName = $appName;
    }

    /**
     * Get the url of the app
     * @return string
     */
    public function getAppUrl()
    {
        return $this->appUrl;
    }

    /**
     * Set the url of the app
     * @param string $appUrl
     */
    public function setAppUrl($appUrl)
    {
        $this->appUrl = $appUrl;
    }

    /**
     * Get the secret of the app
     * @return string
     */
    public function getAppSecret()
    {
        return $this->appSecret;
    }

    /**
     * Set the secret of the app
     * @param string $appSecret
     */
    public function setAppSecret($appSecret)
    {
        $this->appSecret = $appSecret;
    }
}
