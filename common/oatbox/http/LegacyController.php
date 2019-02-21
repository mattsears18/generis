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
 * Copyright (c) 2019 (original work) Open Assessment Technologies SA
 *
 */

namespace oat\oatbox\http;

use Context;

/**
 * Class LegacyController
 * @package oat\oatbox\http
 *
 * @deprecated
 */
abstract class LegacyController extends Controller
{
    protected $response, $request;

    /**
     * @deprecated Use getPsrResponse() instead
     *
     * @return \Request
     */
    public function getRequest()
    {
        $this->maskAsDeprecatedCall();
        return Context::getInstance()->getRequest();
    }

    /**
     * @deprecated Use getPsrResponse() instead
     *
     * @return \Response
     */
    public function getResponse()
    {
        $this->maskAsDeprecatedCall();
        return Context::getInstance()->getResponse();
    }

    /**
     * Get all parameters that includes:
     *  - POST parameters
     *  - GET parameters
     *  - request attributes
     *  - http headers
     * For security reasons, this method will be removed.
     *
     * @return array
     *
     * @deprecated Please use relative method to fetch http parameters.
     */
    public function getRequestParameters()
    {
        return $this->getRequest()->getParameters();
    }

    /**
     * Check if $name exists in request parameters.
     * For security reasons, this method will be removed.
     *
     * @return bool
     *
     * @deprecated Please use relative method to check http parameters.
     */
    public function hasRequestParameter($name)
    {
        return $this->getRequest()->getParameter($name);
    }

    /**
     * Get parameter from request parameters.
     * For security reasons, this method will be removed.
     *
     * @return mixed
     *
     * @deprecated Please use relative method to fetch http parameters.
     */
    public function getRequestParameter($name)
    {
        return $this->getRequest()->getParameter($name);
    }

    /**
     * @see parent::getHeaders()
     *
     * @return array
     */
    public function getHeaders()
    {
        if (!$this->request) {
            return $this->getRequest()->getHeaders();
        }
        return parent::getHeaders();
    }

    /**
     * @see parent::hasHeader()
     *
     * @return bool
     */
    public function hasHeader($name)
    {
        $name = strtolower($name);
        if (!$this->request) {
            return $this->getRequest()->hasHeader($name);
        }
        return parent::hasHeader($name);
    }

    /**
     * @see parent::getHeader()
     *
     * @return mixed
     */
    public function getHeader($name)
    {
        if (!$this->request) {
            return $this->getRequest()->getHeader($name);
        }
        return parent::getHeader($name);
    }

    /**
     * @see parent::hasCookie()
     *
     * @return bool
     */
    public function hasCookie($name)
    {
        if (!$this->request) {
            return $this->getRequest()->hasCookie($name);
        }
        return parent::hasCookie($name);
    }

    /**
     * @see parent::getCookie()
     *
     * @param $name
     * @return bool|mixed
     */
    public function getCookie($name)
    {
        if (!$this->request) {
            return $this->getRequest()->getCookie($name);
        }
        return parent::getCookie($name);
    }

    /**
     * @see parent::getRequestMethod()
     *
     * @return string
     */
    public function getRequestMethod()
    {
        if (!$this->request) {
            return $this->getRequest()->getMethod();
        }
        return parent::getRequestMethod();
    }

    /**
     * @see parent::isRequestGet()
     *
     * @return bool
     */
    public function isRequestGet()
    {
        if (!$this->request) {
            return $this->getRequest()->isGet();
        }
        return parent::isRequestGet();
    }

    /**
     * @see parent::isRequestPost()
     *
     * @return bool
     */
    public function isRequestPost()
    {
        if (!$this->request) {
            return $this->getRequest()->isPost();
        }
        return parent::isRequestPost();
    }

    /**
     * @see parent::isRequestPut()
     *
     * @return bool
     */
    public function isRequestPut()
    {
        if (!$this->request) {
            return $this->getRequest()->isPut();
        }
        return parent::isRequestPut();
    }

    /**
     * @see parent::isRequestDelete()
     *
     * @return bool
     */
    public function isRequestDelete()
    {
        if (!$this->request) {
            return $this->getRequest()->isDelete();
        }
        return parent::isRequestDelete();
    }

    /**
     * @see parent::isRequestHead()
     *
     * @return bool
     */
    public function isRequestHead()
    {
        if (!$this->request) {
            return $this->getRequest()->isHead();
        }
        return parent::isRequestHead();
    }

    /**
     * @see parent::getUserAgent()
     *
     * @return string[]
     */
    public function getUserAgent()
    {
        if (!$this->request) {
            return $this->getRequest()->getUserAgent();
        }
        return parent::getUserAgent();
    }

    /**
     * @see parent::getQueryString()
     *
     * @return string
     */
    public function getQueryString()
    {
        if (!$this->request) {
            return $this->getRequest()->getQueryString();
        }
        return parent::getQueryString();
    }

    /**
     * @see parent::getRequestUri()
     *
     * @return string
     */
    public function getRequestURI()
    {
        if (!$this->request) {
            return $this->getRequest()->getRequestURI();
        }
        return parent::getRequestURI();
    }

    /**
     * @see parent::setCookie()
     *
     * @param $name
     * @param null $value
     * @param null $expire
     * @param null $domainPath
     * @param null $https
     * @param null $httpOnly
     * @return bool|void
     */
    public function setCookie($name, $value = null, $expire = null,
                              $domainPath = null, $https = null, $httpOnly = null)
    {
        if (!$this->response) {
            return $this->getResponse()->setCookie($name, $value, $expire, $domainPath, $https, $httpOnly);
        }
        return parent::setCookie($name, $value, $expire, $domainPath, $https, $httpOnly);
    }

    /**
     * @see parent::setContentHeader()
     *
     * @param $contentType
     * @param string $charset
     * @return Controller
     */
    public function setContentHeader($contentType, $charset = 'UTF-8')
    {
        if (!$this->response) {
           return $this->getResponse()->setContentHeader($contentType, $charset);
        }
        return parent::setContentHeader($contentType, $charset);
    }

    /**
     * @see parent getContentType()
     *
     * @return string|string[]
     */
    public function getContentType()
    {
        if (!$this->response) {
            return $this->getResponse()->getContentType();
        }
        return parent::getContentType();
    }

    /**
     * Check if the current request is using AJAX
     *
     * @return bool
     */
    protected function isXmlHttpRequest()
    {
        if (!$this->request) {
            $this->maskAsDeprecatedCall();
            return \tao_helpers_Request::isAjax();
        }
        return parent::isXmlHttpRequest();
    }

    /**
     * Mark a method as deprecated
     * @param null $function
     */
    protected function maskAsDeprecatedCall($function = null)
    {
        $message = '[DEPRECATED]  Deprecated call ';
        if (!is_null($function)) {
            $message .= 'of "' . $function . '"';
        }
        $message .= ' (' . get_called_class() .')';
        \common_Logger::i($message);
    }

}