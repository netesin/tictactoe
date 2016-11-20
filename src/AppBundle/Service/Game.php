<?php

namespace AppBundle\Service;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * User - X
 * Comp - O
 */
class Game implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    private $cells = [
        'c1' => 0,
        'c2' => 0,
        'c3' => 0,
        'c4' => 0,
        'c5' => 0,
        'c6' => 0,
        'c7' => 0,
        'c8' => 0,
        'c9' => 0,
    ];

    /**
     * Start new game.
     *
     * @return array
     */
    public function startNewGame()
    {
        $session = $this->container->get('session');
        $session->set('game.cells', $this->cells);

        return $this->cells;
    }

    /**
     * Get actual game cells
     *
     * @return array
     */
    public function getFreeCell()
    {
        $cells = $this->getCells();

        return array_filter($cells, function ($var) {
            return empty($var);
        });
    }

    /**
     * Get default cells.
     *
     * @return array
     */
    public function getDefaultCells()
    {
        return $this->cells;
    }

    /**
     * Get actual game cells
     *
     * @return array
     */
    public function getCells()
    {
        $session = $this->container->get('session');

        return $session->get('game.cells', $this->cells);
    }

    /**
     * Set cell value.
     *
     * @param string $cell
     * @param string $value X - user, O - comp
     *
     * @return bool
     */
    public function setCell($cell, $value)
    {
        $session = $this->container->get('session');
        $cells   = $session->get('game.cells', $this->cells);

        if (empty($cells[$cell])) {
            $cells[$cell] = $value;
            $session->set('game.cells', $cells);

            return true;
        }

        return false;
    }

    /**
     * Get comp move.
     *
     * @return string
     */
    public function getCompMove()
    {
        $lines = $this->getLines();
        $cell  = null;

        // Find 2 not empty cell in line
        $find2Cell = function ($line) use (&$cell) {
            if (key_exists('0', array_count_values($line)) && key_exists('O', array_count_values($line)) && array_count_values($line)['O'] == 2) {
                $cell = array_search('0', $line);
            }
            if (key_exists('0', array_count_values($line)) && key_exists('X', array_count_values($line)) && array_count_values($line)['X'] == 2) {
                $cell = array_search('0', $line);
            }
        };

        // Process lines
        foreach (array_keys($lines) as $key) {
            $find2Cell($lines[$key]);
        }

        if (empty($cell)) {
            $freeCells = $this->getFreeCell();
            $cell      = array_rand($freeCells, 1);
        }

        if (!empty($cell)) {
            $this->setCell($cell, 'O');
        }

        return $cell;
    }

    /**
     * Set user move.
     *
     * @param string $cell
     *
     * @return bool
     */
    public function setUserMove($cell)
    {
        return $this->setCell($cell, 'X');
    }

    /**
     * Get cell lines.
     *
     * @return array
     */
    protected function getLines()
    {
        $cells = $this->getCells();

        return [
            'l1' => [
                'c1' => $cells['c1'],
                'c2' => $cells['c2'],
                'c3' => $cells['c3'],
            ],
            'l2' => [
                'c1' => $cells['c1'],
                'c4' => $cells['c4'],
                'c7' => $cells['c7'],
            ],
            'l3' => [
                'c1' => $cells['c1'],
                'c5' => $cells['c5'],
                'c9' => $cells['c9'],
            ],
            'l4' => [
                'c2' => $cells['c2'],
                'c5' => $cells['c5'],
                'c8' => $cells['c8'],
            ],
            'l5' => [
                'c3' => $cells['c3'],
                'c6' => $cells['c6'],
                'c9' => $cells['c9'],
            ],
            'l6' => [
                'c3' => $cells['c3'],
                'c5' => $cells['c5'],
                'c7' => $cells['c7'],
            ],
            'l7' => [
                'c4' => $cells['c4'],
                'c5' => $cells['c5'],
                'c6' => $cells['c6'],
            ],
            'l8' => [
                'c7' => $cells['c7'],
                'c8' => $cells['c8'],
                'c9' => $cells['c9'],
            ],
        ];
    }

    /**
     * Get game scope.
     *
     * @return array
     */
    public function getScope()
    {
        $session = $this->container->get('session');

        return $session->get('game.scope', ['X' => 0, 'O' => 0, 'XO' => 0]);
    }

    /**
     * Get winner.
     *
     * @return string|null null - no winner, X - user winner, O - comp winner
     */
    public function getWinner()
    {
        $lines  = $this->getLines();
        $winner = null;

        // Check line
        $checkLine = function ($line) use (&$winner) {
            if (!key_exists('0', array_count_values($line)) && array_search(3, array_count_values($line))) {
                $winner = $line[array_keys($line)[0]];
            }
        };

        foreach (array_keys($lines) as $key) {
            $checkLine($lines[$key]);
        }

        if (empty($winner) && count($this->getFreeCell()) === 0) {
            $winner = 'XO';
        }

        if (!empty($winner)) {

            $this->startNewGame();

            $session = $this->container->get('session');
            $scope   = $this->getScope();
            $scope[$winner]++;

            $session->set('game.scope', $scope);
        }

        return $winner;
    }
}
