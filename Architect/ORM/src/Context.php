<?php
namespace Architect\ORM\src;

/**
 * @Entity @Table(name="contexts")
 */
class Context
{
	/** @Id @Column(type="integer") @GeneratedValue **/
	protected $context_id;

	/** @Column(type="string") **/
	protected $context_name;

	public function getId()
	{
		return $this->context_id;
	}

	public function getContextName()
	{
		return $this->context_name;
	}

	public function setContextName($context_name)
	{
		return $this->context_name = $context_name;
	}
}