<?php


/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class ApiTester extends \Codeception\Actor
{
    use _generated\ApiTesterActions;

    /**
     * Define custom actions here
     */

    /**
     * @param $expected
     * @return mixed|null
     */
    public function seeResponseJsonEquals($expected)
    {
        $this->seeResponseIsJson();
        return $this->seeResponseEquals(json_encode($expected));
    }

    public function grabJsonResponse()
    {
        return json_decode($this->grabResponse(), true);
    }
}
