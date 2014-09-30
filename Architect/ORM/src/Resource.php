<?php
namespace Architect\ORM\src;

/**
 * Architect\ORM\src\Resource
 *
 * Resource object for ORM
 *
 * @category ORM
 * @package Architect
 * @subpackage ORM
 * @author Rob Lowcock <rob.lowcock@gmail.com>
 * @Entity
 * @Table(name="resource")
 */
class Resource
{
	/**
	 * The id of the resource
	 * @var int
	 * @Id
	 * @Column(type="integer")
	 * @GeneratedValue
	 */
	protected $resource_id;

	/**
	 * The name of the resource
	 * @var string
	 * @Column(type="string")
	 */
	protected $resource_name;

	/**
	 * The content of the resource (e.g. url)
	 * @var string
	 * @Column(type="string")
	 */
	protected $content;

	/**
	 * The type of the resource
	 * @var Architect\Orm\src\ResourceType
	 * @ManyToOne(targetEntity="ResourceType", inversedBy="resource")
	 * @JoinColumn(name="resource_type_id", referencedColumnName="resource_type_id")
	 */
	// private $resource_type;

	/**
	 * Get the ID of the resource
	 * @return int
	 */
	public function getId()
	{
		return $this->resource_id;
	}

	/**
	 * Get the name of the resource
	 * @return string
	 */
	public function getResourceName()
	{
		return $this->resource_name;
	}

	/**
	 * Get the content of the resource
	 * @return string
	 */
	public function getContent()
	{
		return $this->content;
	}

	/**
	 * Get the type of the resource
	 * @return Architect\Orm\src\ResourceType
	 */
	// public function getResourceType()
	// {
	// 	return $this->resource_type;
	// }

	/**
	 * Set the resource name
	 * @param string $resource_name
	 */
	public function setResourceName($resource_name)
	{
		$this->resource_name = $resource_name;
	}

	/**
	 * Set the resource content
	 * @param string $content
	 */
	public function setContent($content)
	{
		$this->content = $content;
	}

	/**
	 * Set the type of the resource
	 * @param Architect\Orm\src\ResourceType $resource_type
	 */
	// public function setResourceType($resource_type)
	// {
	// 	$this->resource_type = $resource_type;
	// }
}