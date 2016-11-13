'use strict';

/**
 * Main module
 */
define(
    [
        'angular',
        'jquery'
    ],
    function (angular) {
        
        /**
         * Main module
         */
        
        angular
            .module('app', [])
            .controller('MainController', ['$http', MainController]);
        
        /**
         * Main controller
         */
        
        function MainController($http) {
            
            var self = this;
            
            var defaultCells = {
                c1: null,
                c2: null,
                c3: null,
                c4: null,
                c5: null,
                c6: null,
                c7: null,
                c8: null,
                c9: null
            };
            
            self.cells        = {};
            self.scope        = {};
            self.continue     = null;
            self.userMove     = userMove;
            self.startNewGame = startNewGame;
            self.continueGame = continueGame;
            
            self.isGame = false;
            
            loadGame();
            loadScope();
            
            /**
             * Load game scope
             */
            function loadScope() {
                $http
                    .get('/api/loadScope')
                    .then(
                        function (result) {
                            if (result.data) {
                                self.scope = result.data;
                            }
                        }
                    );
            }
            
            /**
             * Load game
             */
            function loadGame() {
                $http
                    .get('/api/loadGame')
                    .then(
                        function (result) {
                            if (result.data) {
                                self.continue = result.data;
                            }
                        }
                    );
            }
            
            /**
             * Continue game
             */
            function continueGame() {
                if (self.continue) {
                    startGame(self.continue);
                }
            }
            
            /**
             * Start new game
             */
            function startNewGame() {
                $http
                    .post('/api/startNewGame')
                    .then(
                        function (result) {
                            startGame(result.data)
                        }
                    );
            }
            
            /**
             * Start game
             */
            function startGame(cells) {
                cells          = cells || defaultCells;
                self.cells     = cells;
                self.isGame    = true;
                self.winnerMsg = undefined;
            }
            
            /**
             * Show winner
             */
            function showWinner(winner) {
                
                var winnerMsg;
                
                if (winner === 'X') {
                    winnerMsg = 'You win :)';
                    self.scope['X']++;
                } else if (winner === 'O') {
                    winnerMsg = 'Comp is win :(';
                    self.scope['O']++;
                } else if (winner === 'XO') {
                    winnerMsg = 'Draw';
                    self.scope['XO']++;
                }
                
                self.winnerMsg = winnerMsg;
            }
            
            /**
             * Get comp move.
             */
            function getCompMove() {
                
                $http
                    .get('/api/getCompMove')
                    .then(
                        function (result) {
                            
                            if (result.data.success) {
                                self.cells[result.data.cell] = 'O';
                                
                                if (result.data.winner) {
                                    showWinner(result.data.winner)
                                }
                                
                            } else {
                                alert('False get comp move :(');
                            }
                            
                        }
                    );
                
            }
            
            /**
             * User move.
             *
             * @param cell
             */
            function userMove(cell) {
                
                if (!self.cells[cell]) {
                    
                    $http
                        .post('/api/setUserMove', {cell: cell})
                        .then(
                            function (result) {
                                
                                if (result.data.success) {
                                    self.cells[cell] = 'X';
                                    
                                    if (result.data.winner) {
                                        showWinner(result.data.winner)
                                    } else {
                                        
                                        // Get comp move
                                        getCompMove();
                                    }
                                    
                                } else {
                                    alert('False set you move, try again...');
                                }
                                
                            }
                        );
                    
                }
                
            }
            
        }
        
        // On ready run.
        
        require(['domReady!'], function (document) {
            
            angular.bootstrap(document, ['app']);
            
            angular.element('#main').removeAttr('style');
            angular.element('#loader').css({display: 'none'});
            angular.element('body').css({overflow: 'auto'});
            
        });
    }
);

