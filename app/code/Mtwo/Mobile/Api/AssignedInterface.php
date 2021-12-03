<?php

namespace Mtwo\Mobile\Api;

interface AssignedInterface {

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * Get 
     *
     * @return int|null
     */
    public function getProductId();

    /**
     * Get 
     *
     * @return int|null
     */
    public function getOwnerId();

    /**
     * Get 
     *
     * @return int|null
     */
    public function getSellerId();

    /**
     * Get 
     *
     * @return string
     */
    public function getShopTitle();

    /**
     * Get 
     *
     * @return string
     */
    public function getShopUrl();

    /**
     * Set ID
     *
     * @param int $id
     * @return \Mtwo\Mobile\Api\AssignedInterface
     */
    public function setId($id);

    /**
     * Set ID
     *
     * @param int $id
     * @return \Mtwo\Mobile\Api\AssignedInterface
     */
    public function setProductId($id);

    /**
     * Set ID
     *
     * @param int $id
     * @return \Mtwo\Mobile\Api\AssignedInterface
     */
    public function setOwnerId($id);

    /**
     * Set ID
     *
     * @param int $id
     * @return \Mtwo\Mobile\Api\AssignedInterface
     */
    public function setSellerId($id);

    /**
     * Set ID
     *
     * @param string $title
     * @return \Mtwo\Mobile\Api\AssignedInterface
     */
    public function setShopTitle($title);

    /**
     * Set ID
     *
     * @param string $title
     * @return \Mtwo\Mobile\Api\AssignedInterface
     */
    public function setShopUrl($title);
}
