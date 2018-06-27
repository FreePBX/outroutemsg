<?php
namespace FreePBX\modules\Outroutemsg;
use FreePBX\modules\Backup as Base;
class Backup Extends Base\BackupBase{
  public function runBackup($id,$transaction){
    $this->addDependency('recordings');
    $this->addConfigs($this->FreePBX->Outroutemsg->get());
  }
}