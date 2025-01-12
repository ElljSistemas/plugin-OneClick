<?php

namespace OneClick;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use MapasCulturais\Traits\EntityMetadata;

/**
 * Settings
 *
 * @property-read int $id
 * 
 * @property object $metadata 
 * @property DateTime $createTimestamp 
 * @property DateTime $updateTimestamp 
 * @property int $status 
 * 
 * 
 * @ORM\Table(name="settings")
 * @ORM\Entity
 * @ORM\entity(repositoryClass="OneClick\Repositories\Settings")
 */
class Settings extends \MapasCulturais\Entity
{
    use EntityMetadata;

    const STATUS_ACTIVE = 1;
    const STATUS_DISABLED = 2;

    protected $__enableMagicGetterHook = true;
    protected $__enableMagicSetterHook = true;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="settings_id_seq", allocationSize=1, initialValue=1)
     */
    protected $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="smallint", nullable=false)
     */
    protected $status = self::STATUS_ACTIVE;


    /**
     * @var object
     *
     * @ORM\Column(name="metadata", type="json", nullable=false)
     */
    protected $metadata;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_timestamp", type="datetime", nullable=false)
     */
    protected $createTimestamp;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="update_timestamp", type="datetime", nullable=true)
     */
    protected $updateTimestamp;

    /**
     * @var integer
     *
     * @ORM\Column(name="subsite_id", type="integer", nullable=true)
     */
    protected $subsiteId;

    /**
    * @ORM\OneToMany(targetEntity="OneClick\SettingsMeta", mappedBy="owner", cascade={"remove"}, orphanRemoval=true)
    */
    protected $__metadata = [];

}
