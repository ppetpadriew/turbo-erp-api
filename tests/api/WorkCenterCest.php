<?php

namespace App\Tests\Api;


class WorkCenterCest extends BaseCest
{

    protected function getBaseUrl(): string
    {
        return '/work_centers';
    }

    public function testCreateNotSubWorkCenterWithParentWorkCenter()
    {
        // @todo: implement this test
        verify(false)->true();

        // It should not be able to set parent work center and return error message
    }

    public function testCreateSubWorkCenterWithParentWorkCenter()
    {
        // @todo: implement this test
        verify(false)->true();

        // It should able to set parent work center
    }

    public function testUpdateNotSubWorkCenterWithParentWorkCenter()
    {
        // @todo: implement this test
        verify(false)->true();

        // It should not be able to set parent work center and return error message
    }

    public function testUpdateSubWorkCenterWithParentWorkCenter()
    {
        // @todo: implement this test
        verify(false)->true();

        // It should able to set parent work center
    }

    // @todo: test when setting costing work center, it should check the type of given work center if it is costing type or not.
}
