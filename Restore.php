<?php
namespace FreePBX\modules\Outroutemsg;
use FreePBX\modules\Backup as Base;
class Restore Extends Base\RestoreBase{
  public function runRestore(){
    $configs = $this->getConfigs();
    $this->FreePBX->Outroutemsg->set($configs['default_msg_id'],  $configs['intracompany_msg_id'],  $configs['emergency_msg_id'],  $configs['no_answer_msg_id'],  $configs['invalidnmbr_msg_id']);
  }

  public function processLegacy($pdo, $data, $tables, $unknownTables){
    $this->restoreLegacyDatabase($pdo);
  }
}
