<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Bizproc\Workflow\Entity\WorkflowInstanceTable;
use Bitrix\Main\Loader;

Loader::includeModule('bizproc');
Loader::includeModule('crm');

$allStartedBizproc = WorkflowInstanceTable::getList([
    'select' => [
        'ID',
        'MODULE_ID',
        'ENTITY',
        'DOCUMENT_ID'
    ],
    /**
     * if necessary, set a limit
     */
    'limit' => 10000
])->fetchAll();

$errors = [];

foreach ($allStartedBizproc as $startedBizproc) {
    $error = [];
    CBPDocument::terminateWorkflow($startedBizproc['ID'], [
        $startedBizproc['MODULE_ID'],
        $startedBizproc['ENTITY'],
        $startedBizproc['DOCUMENT_ID']
    ], $error);
    if (count($error) > 0) {
        $errors[] = $error;
    }
}

print_r($errors);
