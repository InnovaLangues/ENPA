<?php

namespace Claroline\CoreBundle\Library\Event;

use Symfony\Component\EventDispatcher\Event;
use Claroline\CoreBundle\Entity\Workspace\AbstractWorkspace;

class ImportWidgetConfigEvent extends Event
{
    private $config;
    private $workspace;
    private $archive;

    public function __construct(array $config, AbstractWorkspace $workspace, \ZipArchive $archive)
    {
        $this->config = $config;
        $this->workspace = $workspace;
        $this->archive = $archive;
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function getWorkspace()
    {
        return $this->workspace;
    }
    
    public function getArchive()
    {
        return $this->archive;
    }
}

