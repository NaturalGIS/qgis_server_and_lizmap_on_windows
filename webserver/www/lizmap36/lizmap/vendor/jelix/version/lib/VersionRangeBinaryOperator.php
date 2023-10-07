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
 * Represents a binary operator (AND or OR) in a version range expression.
 */
class VersionRangeBinaryOperator implements VersionRangeOperatorInterface
{
    const OP_OR = 0;

    const OP_AND = 1;

    protected $op = -1;

    /**
     * @var VersionRangeOperatorInterface|null
     */
    protected $left = null;

    /**
     * @var VersionRangeOperatorInterface|null
     */
    protected $right = null;

    /**
     * @param int $operator one of OP_*
     */
    public function __construct($operator,
                                VersionRangeOperatorInterface $left,
                                VersionRangeOperatorInterface $right)
    {
        $this->op = $operator;
        $this->left = $left;
        $this->right = $right;
    }

    public function compare(Version $value)
    {
        if ($this->op == self::OP_OR) {
            if ($this->left->compare($value)) {
                return true;
            }
            if ($this->right->compare($value)) {
                return true;
            }

            return false;
        }
        if (!$this->left->compare($value)) {
            return false;
        }
        if (!$this->right->compare($value)) {
            return false;
        }

        return true;
    }

    public function __toString()
    {

        return '('.((string)$this->left).') '.($this->op?'AND':'OR').' ('.((string)$this->right).')';
    }
}
