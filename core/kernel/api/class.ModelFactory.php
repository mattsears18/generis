<?php

/**
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; under version 2
 * of the License (non-upgradable).
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * Copyright (c) 2013 (original work) Open Assessment Technologies SA (under the project TAO-PRODUCT);
 *
 * @author  "Lionel Lecaque, <lionel@taotesting.com>"
 * @license GPLv2
 * @package generis
 *
 */

use oat\generis\Helper\UuidPrimaryKeyTrait;

class core_kernel_api_ModelFactory
{
    use UuidPrimaryKeyTrait;

    const DEFAULT_AUTHOR = 'http://www.tao.lu/Ontologies/TAO.rdf#installator';

    /** @var core_kernel_classes_DbWrapper */
    private $dbWrapper;

    public function __construct()
    {
        // @TODO: inject dbWrapper as a dependency.
        $this->dbWrapper = core_kernel_classes_DbWrapper::singleton();
    }

    /**
     * @param $namespace
     * @return mixed
     * @author "Lionel Lecaque, <lionel@taotesting.com>"
     */
    public function getModelId($namespace)
    {
        if (substr($namespace, -1) !== '#') {
            $namespace .= '#';
        }

        $query = 'SELECT modelid FROM models WHERE (modeluri = ?)';
        $results = $this->dbWrapper->query($query, [$namespace]);

        return $results->fetchColumn(0);
    }

    /**
     * @param string $namespace
     *
     * @return string new added model id
     * @throws Exception
     * @author "Lionel Lecaque, <lionel@taotesting.com>"
     */
    public function addNewModel($namespace)
    {
        $modelId = $this->getUniquePrimaryKey();

        $this->dbWrapper->insert('models', ['modelid' => $modelId, 'modeluri' => $namespace]);

        return $modelId;
    }

    /**
     * @param string $namespace
     * @param string $data xml content
     * @return bool Were triples added?
     * @throws EasyRdf_Exception
     * @author "Lionel Lecaque, <lionel@taotesting.com>"
     */
    public function createModel($namespace, $data)
    {
        $modelId = $this->getModelId($namespace);
        if ($modelId === false) {
            common_Logger::d('modelId not found, need to add namespace ' . $namespace);
            $modelId = $this->addNewModel($namespace);
        }
        $modelDefinition = new EasyRdf_Graph($namespace);
        if (is_file($data)) {
            $modelDefinition->parseFile($data);
        } else {
            $modelDefinition->parse($data);
        }
        $format = EasyRdf_Format::getFormat('php');

        $data = $modelDefinition->serialise($format);

        $returnValue = false;

        foreach ($data as $subjectUri => $propertiesValues) {
            foreach ($propertiesValues as $prop => $values) {
                foreach ($values as $v) {
                    $returnValue |= $this->addStatement($modelId, $subjectUri, $prop, $v['value'], isset($v['lang']) ? $v['lang'] : null);
                }
            }
        }

        return $returnValue;
    }

    /**
     * Adds a statement to the ontology if it does not exist yet
     *
     * @param int $modelId
     * @param string $subject
     * @param string $predicate
     * @param string $object
     * @param string $lang
     * @param string $author
     * @return bool Was a row added?
     *
     * @throws Exception
     * @author "Joel Bout, <joel@taotesting.com>"
     */
    public function addStatement($modelId, $subject, $predicate, $object, $lang = null, $author = self::DEFAULT_AUTHOR)
    {
        // Casting values and types.
        $object = (string) $object;
        if (is_null($lang)) {
            $lang = '';
        }

        // TODO: refactor this to use a triple store abstraction.
        $result = $this->dbWrapper->query(
            'SELECT count(*) FROM statements WHERE modelid = ? AND subject = ? AND predicate = ? AND object = ? AND l_language = ?',
            [$modelId, $subject, $predicate, $object, $lang]
        );

        if (intval($result->fetchColumn()) > 0) {
            return false;
        }

        $date = $this->dbWrapper->getPlatForm()->getNowExpression();

        return (bool) $this->dbWrapper->insert(
            'statements',
            [
                'id' => $this->getUniquePrimaryKey(),
                'modelid' => $modelId,
                'subject' => $subject,
                'predicate' => $predicate,
                'object' => $object,
                'l_language' => $lang,
                'author' => is_null($author) ? '' : $author,
                'epoch' => $date,
            ]
        );
    }
}
