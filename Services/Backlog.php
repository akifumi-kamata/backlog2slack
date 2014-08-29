<?php
/**
 * PHP Client for Backlog API
 *
 * PHP versions 5
 *
 * LICENSE: This source file is subject to version 3.0 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_0.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 *
 * @category   Services
 * @package    Services_Backlog
 * @author     devworks <smoochynet@gmail.com>
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    0.1.0
 * @see        http://www.backlog.jp/api/
 * @uses       XML_RPC
 */
require_once 'XML/RPC.php';
require_once 'Services/Backlog/Exception.php';
/*
 * PHP Client for Backlog API
 *
 * PHP versions 5
 *
 * @package    Services_Backlog
 * @author     devworks <smoochynet@gmail.com>
 * @version    Release: 0.1.0
 */
class Services_Backlog {
    /**
     * パス
     * @var string PATH
     */
    const PATH = '/XML-RPC';

    /**
     * プレフィックス
     *
     * @var string PREFIX
     */
    const PREFIX = 'backlog.';

    /**
     * ポート
     */
    const PORT = 443;

    /**
     * XML
     *
     * @var XML_RPC_Client $client
     */
    protected $client;

    /**
     * エラーコード
     */
    const INVALID_ARGMENTS = 101;
    /**
     * コンストラクタ
     * 
     * @param string $url
     * @param string $user
     * @param string $password
     */
    public function __construct($host, $user, $password) {
        if (is_null($host) || is_null($user) || is_null($password)) {
            throw new Services_Backlog_Exception('invalid argments', self::INVALID_ARGMENTS);
        }
        $this->client = new XML_RPC_Client(self::PATH, $host, self::PORT);
        $this->client->setCredentials($user, $password);
    }

    /**
     * 参加プロジェクトを取得する
     * 
     * @return array APIの結果
     */
    public function getProjects() {
        $message = new XML_RPC_Message(self::PREFIX . 'getProjects');
        return $this->_send($message);
    }

    /**
     * 指定したキーのプロジェクトを取得する
     * 
     * @param  string|integer $projectKey
     * @return array          APIの結果
     */
    public function getProject($projectKey) {
        if (is_null($projectKey)) {
            throw new Services_Backlog_Exception('invalid argments', self::INVALID_ARGMENTS);
        }
        $paramKey = null;
        if (is_numeric($projectKey)) {
            $paramKey = new XML_RPC_Value(intval($projectKey), 'int');
        } else {
            $paramKey = new XML_RPC_Value($projectKey, 'string');
        }
        $message = new XML_RPC_Message(self::PREFIX . 'getProject', array($paramKey));
        return $this->_send($message);
    }

    /**
     * プロジェクトのカテゴリを取得する
     *
     * @param  integer $projectId
     * @return array   APIの結果
     */
    public function getComponents($projectId) {
        if (is_null($projectId)) {
            throw new Services_Backlog_Exception('invalid argments', self::INVALID_ARGMENTS);
        }
        $paramId = new XML_RPC_Value($projectId, 'int');
        $message = new XML_RPC_Message(self::PREFIX . 'getComponents', array($paramId));
        return $this->_send($message);
    }
    /**
     * プロジェクトの発生バージョン/マイルストーンを取得する
     * 
     * @param  integer $projectId
     * @return array   APIの結果
     */
    public function getVersions($projectId) {
        if (is_null($projectId)) {
            throw new Services_Backlog_Exception('invalid argments', self::INVALID_ARGMENTS);
        }
        $paramId = new XML_RPC_Value($projectId, 'int');
        $message = new XML_RPC_Message(self::PREFIX . 'getVersions', array($paramId));
        return $this->_send($message);
    }

    /**
     * プロジェクトの発生バージョン/マイルストーンを取得する
     *
     * @param  integer $projectId
     * @return array   APIの結果
     */
    public function getUsers($projectId) {
        if (is_null($projectId)) {
            throw new Services_Backlog_Exception('invalid argments', self::INVALID_ARGMENTS);
        }
        $paramId = new XML_RPC_Value($projectId, 'int');
        $message = new XML_RPC_Message(self::PREFIX . 'getUsers', array($paramId));
        return $this->_send($message);
    }

    /**
     * プロジェクトの種別を取得する
     *
     * @param  integer $projectId
     * @return array   APIの結果
     */
    public function getIssueTypes($projectId) {
        if (is_null($projectId)) {
            throw new Services_Backlog_Exception('invalid argments', self::INVALID_ARGMENTS);
        }
        $paramId = new XML_RPC_Value($projectId, 'int');
        $message = new XML_RPC_Message(self::PREFIX . 'getIssueTypes', array($paramId));
        return $this->_send($message);
    }
    /**
     * プロジェクトの種別を取得する
     *
     * @param  string|integer  $issueKey
     * @return array           APIの結果
     */
    public function getIssue($issueKey) {
        if (is_null($issueKey)) {
            throw new Services_Backlog_Exception('invalid argments', self::INVALID_ARGMENTS);
        }
        $paramId = null;
        if (is_numeric($issueKey)) {
            $paramId = new XML_RPC_Value(intval($issueKey), 'int');
        } else {
            $paramId = new XML_RPC_Value($issueKey, 'string');
        }
        $message = new XML_RPC_Message(self::PREFIX . 'getIssue', array($paramId));
        return $this->_send($message);
    }

    /**
     * 課題のコメントを取得する
     *
     * @param  integer $issueId
     * @return array   APIの結果
     */
    public function getComments($issueId) {
        if (is_null($issueId)) {
            throw new Services_Backlog_Exception('invalid argments', self::INVALID_ARGMENTS);
        }
        $paramId = new XML_RPC_Value($issueId, 'int');
        $message = new XML_RPC_Message(self::PREFIX . 'getComments', array($paramId));
        return $this->_send($message);
    }

    /**
     *　課題の件数を取得する
     * 
     * @param  array   パラメータ
     * @return array   APIの結果
     */
    public function countIssue($params = array()) {
        if (!array_key_exists('projectId', $params) || !isset ($params['projectId'])) {
            throw new Services_Backlog_Exception('invalid argments', self::INVALID_ARGMENTS);
        }
        $xmlParams = array();
        $xmlParams['projectId'] = new XML_RPC_Value($params['projectId'], 'int');

        if (array_key_exists('issueTypeId', $params) && isset ($params['issueTypeId'])) {
            $xmlParams['issueTypeId'] = new XML_RPC_Value($params['issueTypeId'], 'string');
        }
        if (array_key_exists('componentId', $params) && isset ($params['componentId'])) {
            if (is_array($params['componentId'])) {
                $c = array();
                foreach ($params['componentId'] as $component) {
                    array_push($c, new XML_RPC_Value($component, 'int'));
                }
                $xmlParams['componentId'] = new XML_RPC_Value($c, 'array');
            } else {
                $xmlParams['componentId'] = new XML_RPC_Value($params['componentId'], 'int');
            }
        }
        if (array_key_exists('versionId', $params) && isset ($params['versionId'])) {
            if (is_array($params['versionId'])) {
                $c = array();
                foreach ($params['versionId'] as $component) {
                    array_push($c, new XML_RPC_Value($component, 'int'));
                }
                $xmlParams['versionId'] = new XML_RPC_Value($c, 'array');
            } else {
                $xmlParams['versionId'] = new XML_RPC_Value($params['versionId'], 'int');
            }
        }
        if (array_key_exists('milestoneId', $params) && isset ($params['milestoneId'])) {
            if (is_array($params['milestoneId'])) {
                $c = array();
                foreach ($params['milestoneId'] as $component) {
                    array_push($c, new XML_RPC_Value($component, 'int'));
                }
                $xmlParams['milestoneId'] = new XML_RPC_Value($c, 'array');
            } else {
                $xmlParams['milestoneId'] = new XML_RPC_Value($params['milestoneId'], 'int');
            }
        }
        if (array_key_exists('statusId', $params) && isset ($params['statusId'])) {
            if (is_array($params['statusId'])) {
                $c = array();
                foreach ($params['statusId'] as $component) {
                    array_push($c, new XML_RPC_Value($component, 'int'));
                }
                $xmlParams['statusId'] = new XML_RPC_Value($c, 'array');
            } else {
                $xmlParams['statusId'] = new XML_RPC_Value($params['statusId'], 'int');
            }
        }
        if (array_key_exists('priorityId', $params) && isset ($params['priorityId'])) {
            if (is_array($params['priorityId'])) {
                $c = array();
                foreach ($params['priorityId'] as $component) {
                    array_push($c, new XML_RPC_Value($component, 'int'));
                }
                $xmlParams['priorityId'] = new XML_RPC_Value($c, 'array');
            } else {
                $xmlParams['priorityId'] = new XML_RPC_Value($params['priorityId'], 'int');
            }
        }
        if (array_key_exists('assignerId', $params) && isset ($params['assignerId'])) {
            if (is_array($params['assignerId'])) {
                $c = array();
                foreach ($params['assignerId'] as $component) {
                    array_push($c, new XML_RPC_Value($component, 'int'));
                }
                $xmlParams['assignerId'] = new XML_RPC_Value($c, 'array');
            } else {
                $xmlParams['assignerId'] = new XML_RPC_Value($params['assignerId'], 'int');
            }
        }
        if (array_key_exists('createdUserId', $params) && isset ($params['createdUserId'])) {
            if (is_array($params['createdUserId'])) {
                $c = array();
                foreach ($params['createdUserId'] as $component) {
                    array_push($c, new XML_RPC_Value($component, 'int'));
                }
                $xmlParams['createdUserId'] = new XML_RPC_Value($c, 'array');
            } else {
                $xmlParams['createdUserId'] = new XML_RPC_Value($params['createdUserId'], 'int');
            }
        }
        if (array_key_exists('resolutionId', $params) && isset ($params['resolutionId'])) {
            if (is_array($params['resolutionId'])) {
                $c = array();
                foreach ($params['resolutionId'] as $component) {
                    array_push($c, new XML_RPC_Value($component, 'int'));
                }
                $xmlParams['resolutionId'] = new XML_RPC_Value($c, 'array');
            } else {
                $xmlParams['resolutionId'] = new XML_RPC_Value($params['resolutionId'], 'int');
            }
        }
        if (array_key_exists('created_on_min', $params) && isset ($params['created_on_min'])) {
            $xmlParams['created_on_min'] = new XML_RPC_Value($params['created_on_min'], 'string');
        }
        if (array_key_exists('created_on_max', $params) && isset ($params['created_on_max'])) {
            $xmlParams['created_on_max'] = new XML_RPC_Value($params['created_on_max'], 'string');
        }
        if (array_key_exists('updated_on_min', $params) && isset ($params['updated_on_min'])) {
            $xmlParams['updated_on_min'] = new XML_RPC_Value($params['updated_on_min'], 'string');
        }
        if (array_key_exists('updated_on_max', $params) && isset ($params['updated_on_max'])) {
            $xmlParams['updated_on_max'] = new XML_RPC_Value($params['updated_on_max'], 'string');
        }
        if (array_key_exists('start_date_min', $params) && isset ($params['start_date_min'])) {
            $xmlParams['start_date_min'] = new XML_RPC_Value($params['start_date_min'], 'string');
        }
        if (array_key_exists('start_date_max', $params) && isset ($params['start_date_max'])) {
            $xmlParams['start_date_max'] = new XML_RPC_Value($params['start_date_min'], 'string');
        }
        if (array_key_exists('due_date_min', $params) && isset ($params['due_date_min'])) {
            $xmlParams['due_date_min'] = new XML_RPC_Value($params['due_date_min'], 'string');
        }
        if (array_key_exists('due_date_max', $params) && isset ($params['due_date_max'])) {
            $xmlParams['due_date_max'] = new XML_RPC_Value($params['due_date_max'], 'string');
        }
        if (array_key_exists('query', $params) && isset ($params['query'])) {
            $xmlParams['query'] = new XML_RPC_Value($params['query'], 'string');
        }
        $message = new XML_RPC_Message(self::PREFIX . 'countIssue', array(new XML_RPC_Value($xmlParams, 'struct')));
        return $this->_send($message);
    }

    /**
     *　課題の検索結果を取得する
     *
     * @param  array   パラメータ
     * @return array   APIの結果
     */
    public function findIssue($params = array()) {
        if (!array_key_exists('projectId', $params) || !isset ($params['projectId'])) {
            throw new Services_Backlog_Exception('invalid argments', self::INVALID_ARGMENTS);
        }
        $xmlParams = array();
        $xmlParams['projectId'] = new XML_RPC_Value($params['projectId'], 'int');
        if (array_key_exists('issueTypeId', $params) && isset ($params['issueTypeId'])) {
            $xmlParams['issueTypeId'] = new XML_RPC_Value($params['issueTypeId'], 'string');
        }
        if (array_key_exists('componentId', $params) && isset ($params['componentId'])) {
            if (is_array($params['componentId'])) {
                $c = array();
                foreach ($params['componentId'] as $component) {
                    array_push($c, new XML_RPC_Value($component, 'int'));
                }
                $xmlParams['componentId'] = new XML_RPC_Value($c, 'array');
            } else {
                $xmlParams['componentId'] = new XML_RPC_Value($params['componentId'], 'int');
            }
        }
        if (array_key_exists('versionId', $params) && isset ($params['versionId'])) {
            if (is_array($params['versionId'])) {
                $c = array();
                foreach ($params['versionId'] as $component) {
                    array_push($c, new XML_RPC_Value($component, 'int'));
                }
                $xmlParams['versionId'] = new XML_RPC_Value($c, 'array');
            } else {
                $xmlParams['versionId'] = new XML_RPC_Value($params['versionId'], 'int');
            }
        }
        if (array_key_exists('milestoneId', $params) && isset ($params['milestoneId'])) {
            if (is_array($params['milestoneId'])) {
                $c = array();
                foreach ($params['milestoneId'] as $component) {
                    array_push($c, new XML_RPC_Value($component, 'int'));
                }
                $xmlParams['milestoneId'] = new XML_RPC_Value($c, 'array');
            } else {
                $xmlParams['milestoneId'] = new XML_RPC_Value($params['milestoneId'], 'int');
            }
        }
        if (array_key_exists('statusId', $params) && isset ($params['statusId'])) {
            if (is_array($params['statusId'])) {
                $c = array();
                foreach ($params['statusId'] as $component) {
                    array_push($c, new XML_RPC_Value($component, 'int'));
                }
                $xmlParams['statusId'] = new XML_RPC_Value($c, 'array');
            } else {
                $xmlParams['statusId'] = new XML_RPC_Value($params['statusId'], 'int');
            }
        }
        if (array_key_exists('priorityId', $params) && isset ($params['priorityId'])) {
            if (is_array($params['priorityId'])) {
                $c = array();
                foreach ($params['priorityId'] as $component) {
                    array_push($c, new XML_RPC_Value($component, 'int'));
                }
                $xmlParams['priorityId'] = new XML_RPC_Value($c, 'array');
            } else {
                $xmlParams['priorityId'] = new XML_RPC_Value($params['priorityId'], 'int');
            }
        }
        if (array_key_exists('assignerId', $params) && isset ($params['assignerId'])) {
            if (is_array($params['assignerId'])) {
                $c = array();
                foreach ($params['assignerId'] as $component) {
                    array_push($c, new XML_RPC_Value($component, 'int'));
                }
                $xmlParams['assignerId'] = new XML_RPC_Value($c, 'array');
            } else {
                $xmlParams['assignerId'] = new XML_RPC_Value($params['assignerId'], 'int');
            }
        }
        if (array_key_exists('createdUserId', $params) && isset ($params['createdUserId'])) {
            if (is_array($params['createdUserId'])) {
                $c = array();
                foreach ($params['createdUserId'] as $component) {
                    array_push($c, new XML_RPC_Value($component, 'int'));
                }
                $xmlParams['createdUserId'] = new XML_RPC_Value($c, 'array');
            } else {
                $xmlParams['createdUserId'] = new XML_RPC_Value($params['createdUserId'], 'int');
            }
        }
        if (array_key_exists('resolutionId', $params) && isset ($params['resolutionId'])) {
            if (is_array($params['resolutionId'])) {
                $c = array();
                foreach ($params['resolutionId'] as $component) {
                    array_push($c, new XML_RPC_Value($component, 'int'));
                }
                $xmlParams['resolutionId'] = new XML_RPC_Value($c, 'array');
            } else {
                $xmlParams['resolutionId'] = new XML_RPC_Value($params['resolutionId'], 'int');
            }
        }
        if (array_key_exists('created_on_min', $params) && isset ($params['created_on_min'])) {
            $xmlParams['created_on_min'] = new XML_RPC_Value($params['created_on_min'], 'string');
        }
        if (array_key_exists('created_on_max', $params) && isset ($params['created_on_max'])) {
            $xmlParams['created_on_max'] = new XML_RPC_Value($params['created_on_max'], 'string');
        }
        if (array_key_exists('updated_on_min', $params) && isset ($params['updated_on_min'])) {
            $xmlParams['updated_on_min'] = new XML_RPC_Value($params['updated_on_min'], 'string');
        }
        if (array_key_exists('updated_on_max', $params) && isset ($params['updated_on_max'])) {
            $xmlParams['updated_on_max'] = new XML_RPC_Value($params['updated_on_max'], 'string');
        }
        if (array_key_exists('start_date_min', $params) && isset ($params['start_date_min'])) {
            $xmlParams['start_date_min'] = new XML_RPC_Value($params['start_date_min'], 'string');
        }
        if (array_key_exists('start_date_max', $params) && isset ($params['start_date_max'])) {
            $xmlParams['start_date_max'] = new XML_RPC_Value($params['start_date_min'], 'string');
        }
        if (array_key_exists('due_date_min', $params) && isset ($params['due_date_min'])) {
            $xmlParams['due_date_min'] = new XML_RPC_Value($params['due_date_min'], 'string');
        }
        if (array_key_exists('due_date_max', $params) && isset ($params['due_date_max'])) {
            $xmlParams['due_date_max'] = new XML_RPC_Value($params['due_date_max'], 'string');
        }
        if (array_key_exists('query', $params) && isset ($params['query'])) {
            $xmlParams['query'] = new XML_RPC_Value($params['query'], 'string');
        }
        if (array_key_exists('sort', $params) && isset ($params['sort'])) {
            $xmlParams['sort'] = new XML_RPC_Value($params['sort'], 'string');
        }
        if (array_key_exists('order', $params) && isset ($params['order'])) {
            $xmlParams['order'] = new XML_RPC_Value($params['order'], 'boolean');
        }
        if (array_key_exists('offset', $params) && isset ($params['offset'])) {
            $xmlParams['offset'] = new XML_RPC_Value($params['offset'], 'int');
        }
        if (array_key_exists('limit', $params) && isset ($params['limit'])) {
            $xmlParams['limit'] = new XML_RPC_Value($params['limit'], 'int');
        }

        $message = new XML_RPC_Message(self::PREFIX . 'findIssue', array(new XML_RPC_Value($xmlParams, 'struct')));
        return $this->_send($message);
    }

    /**
     *　課題を追加する
     *
     * @param  array   パラメータ
     * @return array   APIの結果
     */
    public function createIssue($params = array()) {
        if (!array_key_exists('projectId', $params) || !isset ($params['projectId']) ||
            !array_key_exists('summary', $params) || !isset ($params['summary'])) {
            throw new Services_Backlog_Exception('invalid argments', self::INVALID_ARGMENTS);
        }
        $xmlParams = array();
        $xmlParams['projectId'] = new XML_RPC_Value($params['projectId'], 'int');
        $xmlParams['summary'] = new XML_RPC_Value($params['summary'], 'string');
        if (array_key_exists('description', $params) && isset ($params['description'])) {
            $xmlParams['description'] = new XML_RPC_Value($params['description'], 'string');
        }
        if (array_key_exists('start_date', $params) && isset ($params['start_date'])) {
            $xmlParams['start_date'] = new XML_RPC_Value($params['start_date'], 'string');
        }
        if (array_key_exists('due_date', $params) && isset ($params['due_date'])) {
            $xmlParams['due_date'] = new XML_RPC_Value($params['due_date'], 'string');
        }
        if (array_key_exists('estimated_hours', $params) && isset ($params['estimated_hours'])) {
            $xmlParams['estimated_hours'] = new XML_RPC_Value($params['estimated_hours'], 'double');
        }
        if (array_key_exists('actual_hours', $params) && isset ($params['actual_hours'])) {
            $xmlParams['actual_hours'] = new XML_RPC_Value($params['actual_hours'], 'double');
        }
        if (array_key_exists('issueTypeId', $params) && isset ($params['issueTypeId'])) {
            $xmlParams['issueTypeId'] = new XML_RPC_Value($params['issueTypeId'], 'int');
        }
        if (array_key_exists('issueType', $params) && isset ($params['issueType'])) {
            $xmlParams['issueType'] = new XML_RPC_Value($params['issueType'], 'string');
        }
        if (array_key_exists('componentId', $params) && isset ($params['componentId'])) {
            if (is_array($params['componentId'])) {
                $c = array();
                foreach ($params['componentId'] as $component) {
                    array_push($c, new XML_RPC_Value($component, 'int'));
                }
                $xmlParams['componentId'] = new XML_RPC_Value($c, 'array');
            } else {
                $xmlParams['componentId'] = new XML_RPC_Value($params['issueTypeId'], 'int');
            }
        }
        if (array_key_exists('component', $params) && isset ($params['component'])) {
            if (is_array($params['component'])) {
                $c = array();
                foreach ($params['component'] as $component) {
                    array_push($c, new XML_RPC_Value($component, 'string'));
                }
                $xmlParams['component'] = new XML_RPC_Value($c, 'array');
            } else {
                $xmlParams['component'] = new XML_RPC_Value($params['issueTypeId'], 'string');
            }
        }
        if (array_key_exists('versionId', $params) && isset ($params['versionId'])) {
            if (is_array($params['versionId'])) {
                $c = array();
                foreach ($params['versionId'] as $component) {
                    array_push($c, new XML_RPC_Value($component, 'int'));
                }
                $xmlParams['versionId'] = new XML_RPC_Value($c, 'array');
            } else {
                $xmlParams['versionId'] = new XML_RPC_Value($params['versionId'], 'int');
            }
        }
        if (array_key_exists('version', $params) && isset ($params['version'])) {
            if (is_array($params['version'])) {
                $c = array();
                foreach ($params['version'] as $component) {
                    array_push($c, new XML_RPC_Value($component, 'string'));
                }
                $xmlParams['version'] = new XML_RPC_Value($c, 'array');
            } else {
                $xmlParams['version'] = new XML_RPC_Value($params['versionId'], 'string');
            }
        }
        if (array_key_exists('milestoneId', $params) && isset ($params['milestoneId'])) {
            if (is_array($params['milestoneId'])) {
                $c = array();
                foreach ($params['milestoneId'] as $component) {
                    array_push($c, new XML_RPC_Value($component, 'int'));
                }
                $xmlParams['milestoneId'] = new XML_RPC_Value($c, 'array');
            } else {
                $xmlParams['milestoneId'] = new XML_RPC_Value($params['milestoneId'], 'int');
            }
        }
        if (array_key_exists('milestone', $params) && isset ($params['milestone'])) {
            if (is_array($params['milestone'])) {
                $c = array();
                foreach ($params['milestone'] as $component) {
                    array_push($c, new XML_RPC_Value($component, 'string'));
                }
                $xmlParams['milestone'] = new XML_RPC_Value($c, 'array');
            } else {
                $xmlParams['milestone'] = new XML_RPC_Value($params['milestone'], 'string');
            }
        }
        if (array_key_exists('priorityId', $params) && isset ($params['priorityId'])) {
            if (is_array($params['priorityId'])) {
                $c = array();
                foreach ($params['priorityId'] as $component) {
                    array_push($c, new XML_RPC_Value($component, 'int'));
                }
                $xmlParams['priorityId'] = new XML_RPC_Value($c, 'array');
            } else {
                $xmlParams['priorityId'] = new XML_RPC_Value($params['priorityId'], 'int');
            }
        }
        if (array_key_exists('priority', $params) && isset ($params['priority'])) {
            if (is_array($params['priority'])) {
                $c = array();
                foreach ($params['priority'] as $component) {
                    array_push($c, new XML_RPC_Value($component, 'string'));
                }
                $xmlParams['priority'] = new XML_RPC_Value($c, 'array');
            } else {
                $xmlParams['priority'] = new XML_RPC_Value($params['priority'], 'string');
            }
        }
        if (array_key_exists('assignerId', $params) && isset ($params['assignerId'])) {
            if (is_array($params['assignerId'])) {
                $c = array();
                foreach ($params['assignerId'] as $component) {
                    array_push($c, new XML_RPC_Value($component, 'int'));
                }
                $xmlParams['assignerId'] = new XML_RPC_Value($c, 'array');
            } else {
                $xmlParams['assignerId'] = new XML_RPC_Value($params['assignerId'], 'int');
            }
        }
        $message = new XML_RPC_Message(self::PREFIX . 'createIssue', array(new XML_RPC_Value($xmlParams, 'struct')));
        return $this->_send($message);
    }

    /**
     *　課題を更新する
     *
     * @param  array   パラメータ
     * @return array   APIの結果
     */
    public function updateIssue($params = array()) {
        if (!array_key_exists('key', $params) || !isset ($params['key'])) {
            throw new Services_Backlog_Exception('invalid argments', self::INVALID_ARGMENTS);
        }
        $xmlParams = array();
        $xmlParams['key'] = new XML_RPC_Value($params['key'], 'string');
        if (array_key_exists('summary', $params) && isset ($params['summary'])) {
            $xmlParams['summary'] = new XML_RPC_Value($params['summary'], 'string');
        }
        if (array_key_exists('description', $params) && isset ($params['description'])) {
            $xmlParams['description'] = new XML_RPC_Value($params['description'], 'string');
        }
        if (array_key_exists('start_date', $params) && isset ($params['start_date'])) {
            $xmlParams['start_date'] = new XML_RPC_Value($params['start_date'], 'string');
        }
        if (array_key_exists('due_date', $params) && isset ($params['due_date'])) {
            $xmlParams['due_date'] = new XML_RPC_Value($params['due_date'], 'string');
        }
        if (array_key_exists('estimated_hours', $params) && isset ($params['estimated_hours'])) {
            $xmlParams['estimated_hours'] = new XML_RPC_Value($params['estimated_hours'], '	double');
        }
        if (array_key_exists('actual_hours', $params) && isset ($params['actual_hours'])) {
            $xmlParams['actual_hours'] = new XML_RPC_Value($params['actual_hours'], '	double');
        }
        if (array_key_exists('issueTypeId', $params) && isset ($params['issueTypeId'])) {
            $xmlParams['issueTypeId'] = new XML_RPC_Value($params['issueTypeId'], 'int');
        }
        if (array_key_exists('issueType', $params) && isset ($params['issueType'])) {
            $xmlParams['issueType'] = new XML_RPC_Value($params['issueType'], 'string');
        }
        if (array_key_exists('componentId', $params) && isset ($params['componentId'])) {
            if (is_array($params['componentId'])) {
                $c = array();
                foreach ($params['componentId'] as $component) {
                    array_push($c, new XML_RPC_Value($component, 'int'));
                }
                $xmlParams['componentId'] = new XML_RPC_Value($c, 'array');
            } else {
                $xmlParams['componentId'] = new XML_RPC_Value($params['issueTypeId'], 'int');
            }
        }
        if (array_key_exists('component', $params) && isset ($params['component'])) {
            if (is_array($params['component'])) {
                $c = array();
                foreach ($params['component'] as $component) {
                    array_push($c, new XML_RPC_Value($component, 'string'));
                }
                $xmlParams['component'] = new XML_RPC_Value($c, 'array');
            } else {
                $xmlParams['component'] = new XML_RPC_Value($params['issueTypeId'], 'string');
            }
        }
        if (array_key_exists('versionId', $params) && isset ($params['versionId'])) {
            if (is_array($params['versionId'])) {
                $c = array();
                foreach ($params['versionId'] as $component) {
                    array_push($c, new XML_RPC_Value($component, 'int'));
                }
                $xmlParams['versionId'] = new XML_RPC_Value($c, 'array');
            } else {
                $xmlParams['versionId'] = new XML_RPC_Value($params['versionId'], 'int');
            }
        }
        if (array_key_exists('version', $params) && isset ($params['version'])) {
            if (is_array($params['version'])) {
                $c = array();
                foreach ($params['version'] as $component) {
                    array_push($c, new XML_RPC_Value($component, 'string'));
                }
                $xmlParams['version'] = new XML_RPC_Value($c, 'array');
            } else {
                $xmlParams['version'] = new XML_RPC_Value($params['versionId'], 'string');
            }
        }
        if (array_key_exists('milestoneId', $params) && isset ($params['milestoneId'])) {
            if (is_array($params['milestoneId'])) {
                $c = array();
                foreach ($params['milestoneId'] as $component) {
                    array_push($c, new XML_RPC_Value($component, 'int'));
                }
                $xmlParams['milestoneId'] = new XML_RPC_Value($c, 'array');
            } else {
                $xmlParams['milestoneId'] = new XML_RPC_Value($params['milestoneId'], 'int');
            }
        }
        if (array_key_exists('milestone', $params) && isset ($params['milestone'])) {
            if (is_array($params['milestone'])) {
                $c = array();
                foreach ($params['milestone'] as $component) {
                    array_push($c, new XML_RPC_Value($component, 'string'));
                }
                $xmlParams['milestone'] = new XML_RPC_Value($c, 'array');
            } else {
                $xmlParams['milestone'] = new XML_RPC_Value($params['milestone'], 'string');
            }
        }
        if (array_key_exists('priorityId', $params) && isset ($params['priorityId'])) {
            if (is_array($params['priorityId'])) {
                $c = array();
                foreach ($params['priorityId'] as $component) {
                    array_push($c, new XML_RPC_Value($component, 'int'));
                }
                $xmlParams['priorityId'] = new XML_RPC_Value($c, 'array');
            } else {
                $xmlParams['priorityId'] = new XML_RPC_Value($params['priorityId'], 'int');
            }
        }
        if (array_key_exists('priority', $params) && isset ($params['priority'])) {
            if (is_array($params['priority'])) {
                $c = array();
                foreach ($params['priority'] as $component) {
                    array_push($c, new XML_RPC_Value($component, 'string'));
                }
                $xmlParams['priority'] = new XML_RPC_Value($c, 'array');
            } else {
                $xmlParams['priority'] = new XML_RPC_Value($params['priority'], 'string');
            }
        }
        if (array_key_exists('assignerId', $params) && isset ($params['assignerId'])) {
            $xmlParams['assignerId'] = new XML_RPC_Value($params['assignerId'], 'int');
        }
        if (array_key_exists('resolutionId', $params) && isset ($params['resolutionId'])) {
            $xmlParams['resolutionId'] = new XML_RPC_Value($params['resolutionId'], 'int');
        }
        if (array_key_exists('comment', $params) && isset ($params['comment'])) {
            $xmlParams['comment'] = new XML_RPC_Value($params['comment'], 'string');
        }

        $message = new XML_RPC_Message(self::PREFIX . 'updateIssue', array(new XML_RPC_Value($xmlParams, 'struct')));
        return $this->_send($message);

    }

    /**
     *　課題の状態を変更する
     *
     * @param  array   パラメータ
     * @return array   APIの結果
     */
    public function switchStatus($params = array()) {
        if (!array_key_exists('key', $params) || !isset ($params['key']) ||
            !array_key_exists('statusId', $params) || !isset ($params['statusId'])) {
            throw new Services_Backlog_Exception('invalid argments', self::INVALID_ARGMENTS);
        }
        $xmlParams = array();
        $xmlParams['key'] = new XML_RPC_Value($params['key'], 'string');
        $xmlParams['statusId'] = new XML_RPC_Value($params['statusId'], 'int');
        if (array_key_exists('assignerId', $params) && isset ($params['assignerId'])) {
            $xmlParams['assignerId'] = new XML_RPC_Value($params['assignerId'], 'int');
        }
        if (array_key_exists('resolutionId', $params) && isset ($params['resolutionId'])) {
            $xmlParams['resolutionId'] = new XML_RPC_Value($params['resolutionId'], 'int');
        }
        if (array_key_exists('comment', $params) && isset ($params['comment'])) {
            $xmlParams['comment'] = new XML_RPC_Value($params['comment'], 'string');
        }

        $message = new XML_RPC_Message(self::PREFIX . 'switchStatus', array(new XML_RPC_Value($xmlParams, 'struct')));
        return $this->_send($message);
    }
    /**
     * XML-RPCサーバへリクエストを送信する
     *
     * @access private
     * @param  XML_RPC_Message $message
     * @return array
     */
    private function _send(XML_RPC_Message $message) {
        $result = $this->client->send($message);
        if ($result->faultCode()) {
            throw new Services_Backlog_Exception($result->faultString(), $result->faultCode());
        }
        $xml = $result->serialize();
        return $xml;
    }

    public function getTimeline() {
        $message = new XML_RPC_Message(self::PREFIX . 'getTimeline');
        return $this->_send($message);
    }
}
?>
