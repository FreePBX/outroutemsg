<?php
namespace FreePBX\modules;
use BMO;
use FreePBX_Helpers;
use PDO;
class Outroutemsg extends FreePBX_Helpers implements BMO {
    const DEFAULT_MSG = -1;
    const CONGESTION_TONE = -2;

    public function install() {}
    public function uninstall() {}
    public function doConfigPageInit($page) {}
	public function getActionBar($request) {
        if($request['display'] === 'outroutemsg'){
            return [
                'reset' => [
					'name' => 'reset',
					'id' => 'reset',
					'value' => _('Reset'),
                ],
                'submit' => [
                	'name' => 'submit',
					'id' => 'submit',
					'value' => _('Submit'),
                ],
            ];
        }
        return [];
    }

    public function get(){
        $sql = "SELECT keyword, data FROM outroutemsg";
        $results = $this->Database->query($sql)->fetchAll(PDO::FETCH_KEY_PAIR);
        $results['default_msg_id']      = isset($results['default_msg_id'])      ? $results['default_msg_id']      : DEFAULT_MSG;
        $results['intracompany_msg_id'] = isset($results['intracompany_msg_id']) ? $results['intracompany_msg_id'] : DEFAULT_MSG;
        $results['emergency_msg_id']    = isset($results['emergency_msg_id'])    ? $results['emergency_msg_id']    : DEFAULT_MSG;
        $results['no_answer_msg_id']    = isset($results['no_answer_msg_id'])    ? $results['no_answer_msg_id']    : DEFAULT_MSG;
        $results['invalidnmbr_msg_id']  = isset($results['invalidnmbr_msg_id'])  ? $results['invalidnmbr_msg_id']  : DEFAULT_MSG;
        return $results;
    }

    public function set($default_msg_id, $intracompany_msg_id, $emergency_msg_id, $no_answer_msg_id, $invalidnmbr_msg_id){
        $this->delete();
        $stmt = $this->Database->prepare('INSERT INTO outroutemsg (keyword, data) values (:keyword,:data)');
        $items = [
            'default_msg_id' => $default_msg_id,
            'intracompany_msg_id' => $intracompany_msg_id,
            'emergency_msg_id' => $emergency_msg_id,
            'no_answer_msg_id' => $no_answer_msg_id,
            'invalidnmbr_msg_id' => $invalidnmbr_msg_id,
        ];
        foreach ($items as $key => $value) {
            $stmt->execute([':keyword' => $key, ':data' => $value]);
        }
        return $this;
    }

    public function delete(){
        $sql = "DELETE FROM outroutemsg WHERE `keyword` IN  ('default_msg_id', 'intracompany_msg_id', 'emergency_msg_id', 'no_answer_msg_id', 'invalidnmbr_msg_id')";
        $this->Database->query($sql);
        return $this;
    }
}
