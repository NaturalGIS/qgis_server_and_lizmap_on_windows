<?php
/**
 * @author      Laurent Jouanneau
 * @copyright   2008-2020 Laurent Jouanneau
 *
 * @link        http://www.jelix.org
 * @licence     MIT
 */


namespace Jelix\Version;

/**
 * Represents an unary operator (>,<,=,!=,<=,>=,~) in a version range expression.
 */
class VersionRangeUnaryOperator implements VersionRangeOperatorInterface
{
    const OP_EQ = 0;
    const OP_LT = 1;
    const OP_GT = 2;
    const OP_GTE = 3;
    const OP_LTE = 4;
    const OP_DIFF = 5;

    protected $op = -1;

    protected $operand = null;

    /**
     * @param int     $operator one of OP_*
     * @param Version $version  the version used to compare
     */
    public function __construct($operator, Version $version)
    {
        $this->op = $operator;
        $this->operand = $version;
    }

    public function compare(Version $value)
    {
        $result = VersionComparator::compare($value, $this->operand);
        //$op = array('=', '<','>', '>=', '<=', '!=');
        //echo "Result compare(".$value.", ".$this->operand.") (op= ".$op[$this->op].") = ";
        switch ($this->op) {
            case self::OP_EQ:
                //echo ( $result === 0? 'ok': 'bad')."\n";
                return $result === 0;
            case self::OP_LT:
                //echo ($result === -1 ? 'ok': 'bad')."\n";
                return $result === -1;
            case self::OP_GT:
                //echo ( $result === 1? 'ok': 'bad')."\n";
                return $result === 1;
            case self::OP_LTE:
                //echo ( $result < 1 ? 'ok': 'bad')."\n";
                return $result < 1;
                break;
            case self::OP_GTE:
                //echo ( $result > -1 ? 'ok': 'bad')."\n";
                return $result > -1;
                break;
            case self::OP_DIFF:
                //echo ( $result != 0 ? 'ok': 'bad')."\n";
                return $result != 0;
        }

        return false;
    }

    function __toString()
    {
        $op = array('=', '<','>', '>=', '<=', '!=');
        return $op[$this->op].$this->operand;
    }
}
