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
	 * @Column(type="integer")
	 * @GeneratedValue
	 */
	protected $app_id;

	/**
	 * The name of the app
	 * @var string
	 * @Column(type="string")
	 */
	protected $app_name;

	/**
	 * The URL of the app
	 * @var string
	 * @Column(type="string", nullable=true)
	 */
	protected $app_url;

	/**
	 * The app secret
	 * @var string
	 * @Column(type="string")
	 */
	protected $app_secret;

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
		return $this->app_id;
	}

	/**
	 * Get the name of the app
	 * @return string
	 */
	public function getAppName()
	{
		return $this->app_name;
	}

	/**
	 * Set the name of the app
	 * @param string $app_name
	 */
	public function setAppName($app_name)
	{
		$this->app_name = $app_name;
	}

	/**
	 * Get the url of the app
	 * @return string
	 */
	public function getAppUrl()
	{
		return $this->app_url;
	}

	/**
	 * Set the url of the app
	 * @param string $app_url
	 */
	public function setAppUrl($app_url)
	{
		$this->app_url = $app_url;
	}

	/**
	 * Get the secret of the app
	 * @return string
	 */
	public function getAppSecret()
	{
		return $this->app_secret;
	}

	/**
	 * Set the secret of the app
	 * @param string $app_secret
	 */
	public function setAppSecret($app_secret)
	{
		$this->app_secret = $app_secret;
	}
}