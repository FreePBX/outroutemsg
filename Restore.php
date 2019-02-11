<?php
namespace FreePBX\modules\Outroutemsg;
use FreePBX\modules\Backup as Base;
class Restore Extends Base\RestoreBase{
  public function runRestore($jobid){
    $configs = $this->getConfigs();
    $this->FreePBX->Outroutemsg->set($configs['default_msg_id'],  $configs['intracompany_msg_id'],  $configs['emergency_msg_id'],  $configs['no_answer_msg_id'],  $configs['invalidnmbr_msg_id']);
  }

  public function processLegacy($pdo, $data, $tables, $unknownTables, $tmpfiledir){
    $tables = array_flip($tables + $unknownTables);
    if (!isset($tables['outroutemsg'])) {
      return $this;
    }
    $cb = $this->FreePBX->Outroutemsg;
    $cb->setDatabase($pdo);
    $configs = $cb->Outroutemsg->get();
    $cb->resetDatabase();
    $cb->set($configs['default_msg_id'], $configs['intracompany_msg_id'], $configs['emergency_msg_id'], $configs['no_answer_msg_id'], $configs['invalidnmbr_msg_id']);
    return $this;
  }
}
