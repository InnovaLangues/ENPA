<?php

namespace Claroline\CoreBundle\Entity\Resource;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Claroline\CoreBundle\Entity\Plugin;

/**
 * @ORM\Entity(repositoryClass="Claroline\CoreBundle\Repository\ResourceTypeRepository")
 * @ORM\Table(name="claro_resource_type")
 */
class ResourceType
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255, name="name")
     */
    protected $name;

    /**
     * @ORM\OneToMany(
     *      targetEntity="Claroline\CoreBundle\Entity\Resource\AbstractResource",
     *      mappedBy="resourceType",
     *      cascade={"persist"}
     * )
     */
    protected $abstractResources;

    /**
     * @ORM\OneToMany(
     *      targetEntity="Claroline\CoreBundle\Entity\Resource\ResourceTypeCustomAction",
     *      mappedBy="resourceType",
     *      cascade={"persist"}
     * )
     */
    protected $customActions;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $class;

    /**
     * @ORM\Column(type="boolean", name="is_visible")
     */
    protected $isVisible;

    /**
     * @ORM\Column(type="boolean", name="is_browsable")
     */
    protected $isBrowsable;

    /**
     * @ORM\OneToOne(targetEntity="Claroline\CoreBundle\Entity\Plugin")
     * @ORM\JoinColumn(name="plugin_id", referencedColumnName="id")
     */
    protected $plugin;

    /**
     * @ORM\ManyToMany(
     *      targetEntity="Claroline\CoreBundle\Entity\Resource\MetaType",
     *      cascade={"persist"}
     * )
     * @ORM\JoinTable(name="claro_meta_type_resource_type",
     *      joinColumns={@ORM\JoinColumn(name="resource_type_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="meta_type_id", referencedColumnName="id")}
     * )
     */
    protected $metaTypes;

    /**
     * @ORM\ManyToOne(
     *      targetEntity="Claroline\CoreBundle\Entity\Resource\ResourceType",
     *      inversedBy="children"
     * )
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $parent;

    /**
     * @ORM\OneToMany(
     *      targetEntity="Claroline\CoreBundle\Entity\Resource\ResourceType",
     *      mappedBy="parent"
     * )
     * @ORM\OrderBy({"id" = "ASC"})
     */
    protected $children;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->abstractResources = new ArrayCollection();
        $this->resourceInstances = new ArrayCollection();
        $this->metaTypes = new ArrayCollection();
        $this->customActions = new ArrayCollection();
    }

    /**
     * Returns the resource type id.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the resource type name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the resource type name.
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Returns the resource instances having the resource type.
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getResourceInstances()
    {
        return $this->resourceInstances;
    }

    public function setBrowsable($isBrowsable)
    {
        $this->isBrowsable = $isBrowsable;
    }

    public function getBrowsable()
    {
        return $this->isBrowsable;
    }

    public function setVisible($isVisible)
    {
        $this->isVisible = $isVisible;
    }

    public function isVisible()
    {
        return $this->isVisible;
    }

    public function setPlugin(Plugin $plugin)
    {
        $this->plugin = $plugin;
    }

    public function getPlugin()
    {
        return $this->plugin;
    }

    public function getClass()
    {
        return $this->class;
    }

    public function setClass($class)
    {
        $this->class = $class;
    }

    public function addMetaType($metaType)
    {
        $this->metaTypes->add($metaType);
    }

    public function removeMetaType($metaType)
    {
        $this->metaTypes->removeElement($metaType);
    }

    public function getMetaTypes()
    {
        return $this->metaTypes;
    }

    public function setParent(ResourceType $parent = null)
    {
        $this->parent = $parent;
    }

    public function getCustomActions()
    {
        return $this->customActions;
    }

    public function addCustomAction(ResourceTypeCustomAction $action)
    {
        $this->customActions->add($action);
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function getChildren()
    {
        return $this->children;
    }

    public function getAbstractResources()
    {
        return $this->abstractResources;
    }

    public function addAbstractResource($abstractResource)
    {
        $this->abstractResource->add($abstractResource);
    }
}