@extends('layouts.app')

@section('scripts')

  @if($game -> name == 'Sudoku')

    <script type='text/javascript'>
      "use strict";!function(a){if("function"==typeof bootstrap)bootstrap("bem",a);else if("object"==typeof exports&&"object"==typeof module)module.exports=a();else if("function"==typeof define&&define.amd)define(a);else if("undefined"!=typeof ses){if(!ses.ok())return;ses.makeBem=a}else{if("undefined"==typeof window&&"undefined"==typeof self)throw new Error("This environment was not anticipated by bem. Please file a bug.");var b="undefined"!=typeof window?window:self,c=b.bem;b.bem=a(),b.bem.noConflict=function(){return b.bem=c,this}}}(function(){function a(a){"undefined"!=typeof a.modifier&&(c.modifier=a.modifier),"undefined"!=typeof a.element&&(c.element=a.element)}function b(a){if(!d.validate(a))return null;var b=a.block,e=a.element,f=a.modifiers,g=b,h=[];return!!e&&(g+=""+c.element+e),!!f&&Object.keys(f).forEach(function(a){var d=f[a],i="function"==typeof d?d(b,e,f):d;!!i&&h.push(""+g+c.modifier+a+" ")}),(g+" "+h.join("")).slice(0,-1)}var c={element:"__",modifier:"--"},d={messages:{block:"You must specify the name of block.",element:"Element name must be a string.",modifier:"Modifiers must be supplied in the `{name : bool || fn}` style."},blockName:function(a){return"undefined"!=typeof a&&"string"==typeof a&&a.length?!0:(console.warn(this.messages.block),!1)},element:function(a){return"undefined"!=typeof a&&"string"!=typeof a?(console.warn(this.messages.element),!1):!0},modifiers:function(a){return"undefined"==typeof a||"object"==typeof a&&"[object Object]"===toString.call(a)?!0:(console.warn(this.messages.modifier),!1)},validate:function(a){return this.blockName(a.block)&&this.element(a.element)&&this.modifiers(a.modifiers)}};return{setDelimiters:a,makeClassName:b}});
    </script>
  
    <script type='text/javascript'
      src='https://cdnjs.cloudflare.com/ajax/libs/babel-core/5.8.25/browser-polyfill.min.js'>
    </script>
  
    <script type='text/javascript'
      src='https://cdnjs.cloudflare.com/ajax/libs/babel-core/5.8.25/browser.min.js'>
    </script>
  
    <script type='text/babel' id='worker'>
    
      self.sudoku = null
      
      // Worker Setup
      self.addEventListener('message', (event) => {
        var options = { method: null }
        try {
          options = JSON.parse(event.data);
        } catch (e) {
          console.warn('event.data is misformed', event)
        }
      
        switch (options.method) {
      
          case 'generate':
            var { hints, limit } = options
            self.sudoku = new Sudoku(hints, limit).generate()
    
            self.postMessage({
              success: self.sudoku.success,
              board: self.sudoku.getBoard(),
              solution: self.sudoku.getSolution()
            });
            break;
      
          case 'validate':
            var { map, number, index } = options
            self.postMessage({
              result: sudoku.validate(map, number, index)
            });
            break;
      
        }
      }, false);
    
      // API
      class Sudoku {
        constructor(hints, limit) {
          this.hints = hints
          this.limit = limit || 10000
      
          this._logs = {
            raw: [],
            incidents: {
              limitExceeded: 0,
              notValid: 0,
              noNumbers: 0
            }
          }
      
          this.success = null
    
          this.numbers = () =>
            new Array(9)
              .join(" ")
              .split(" ")
              .map((num , i) => i + 1)
    
          /*
            Will be used in initial map. Each row will be
            consisted of randomly ordered numbers
          */
          this.randomRow = () => {
            var row = []
            var numbers = this.numbers()
            while (row.length < 9) {
              var index = Math.floor(Math.random() * numbers.length)
              row.push(numbers[index])
              numbers.splice(index, 1)
            }
    
            return row
          }
    
          /*
            This is the dummy placeholder for the
            final results. Will be overridden through the
            backtracking process, and at the and, this will
            be the real results.
          */
          this.result = new Array(9 * 9)
            .join(" ")
            .split(" ")
            .map(entry => null)
    
          /*
            Will be used as the nodeTree in the
            process of backtracking. Each cell has 9 alternative
            paths (randomly ordered).
          */
          this.map = new Array(9 * 9)
            .join(" ")
            .split(" ")
            .map(path => this.randomRow())
    
          /*
            Will be used as history in the backtracking
            process for checking if a candidate number is valid.
          */
          this.stack = []
    
          return this
        }
      
        toRows(arr) {
          var row = 0
          var asRows = new Array(9)
            .join(" ")
            .split(" ")
            .map(row => [])
      
          for (let [index, entry] of arr.entries()) {
            asRows[row].push(entry)
    
            if ( !((index + 1) % 9) ) {
              row += 1
            }
          }
    
          return asRows
        }
    
        no(path, index, msg) {
          var number = path[path.length - 1]
          this._logs.raw.push(`no: @${index} [${number}] ${msg} ${path} `)
        }
    
        yes(path, index) {
          this._logs.raw.push(`yes: ${index} ${path}`)
        }
      
        finalLog() {
          console.groupCollapsed('Raw Logs')
          console.groupCollapsed(this._logs.raw)
          console.groupEnd()
          console.groupEnd()
          console.groupCollapsed('Incidents')
          console.groupCollapsed(this._logs.incidents)
          console.groupEnd()
          console.groupEnd()
        }
    
        getBoard() {
          return this.toRows(this.substractCells())
        }
    
        getSolution() {
          return this.toRows(this.result)
        }
    
        substractCells() {
          var _getNonEmptyIndex = () => {
            var index = Math.floor(Math.random() * _result.length)
            return _result[index] ? index : _getNonEmptyIndex()
          }
    
          var _result = this.result.filter(() => true)
    
          while (
            _result.length - this.hints >
            _result.filter(n => !n).length
          ) {
            _result[_getNonEmptyIndex()] = ''
          }
    
          return _result
        }
      
        validate(map, number, index) {
          var rowIndex = Math.floor(index / 9)
          var colIndex = index % 9
    
          var row = map.slice(
            rowIndex * 9, 9 * (rowIndex + 1)
          )
    
          var col = map.filter((e, i) =>
            i % 9 === colIndex
          )
    
          var boxRow = Math.floor(rowIndex / 3)
          var boxCol = Math.floor(colIndex / 3)
    
          var box = map.filter((e, i) =>
            Math.floor(Math.floor(i / 9) / 3) === boxRow &&
            Math.floor((i % 9) / 3) === boxCol
          )
    
          return {
            row: {
              first: row.indexOf(number),
              last: row.lastIndexOf(number)
            },
            col: {
              first: col.indexOf(number),
              last: col.lastIndexOf(number)
            },
            box: {
              first: box.indexOf(number),
              last: box.lastIndexOf(number)
            }
          }
        }
    
        _validate(map, index) {
          if (!map[index].length) {
            return false
          }
    
          this.stack.splice(index, this.stack.length)
      
          var path = map[index]
          var number = path[path.length - 1]
      
          var didFoundNumber = this.validate(this.stack, number, index)
      
          return (
            didFoundNumber.col.first === -1 &&
            didFoundNumber.row.first === -1 &&
            didFoundNumber.box.first === -1
          )
        }
    
        _generate(map, index) {
          if (index === 9 * 9) {
            return true
          }
    
          if (--this.limit < 0) {
            this._logs.incidents.limitExceeded++
            this.no(map[index], index, 'limit exceeded')
            return false
          }
    
          var path = map[index]
    
          if (!path.length) {
            map[index] = this.numbers()
            map[index - 1].pop()
            this._logs.incidents.noNumbers++
            this.no(path, index, 'no numbers in it')
            return false
          }
    
          var currentNumber = path[path.length - 1]
    
          var isValid = this._validate(map, index)
          if (!isValid) {
            map[index].pop()
            map[index + 1] = this.numbers()
            this._logs.incidents.notValid++
            this.no(path, index, 'is not valid')
            return false
          } else {
            this.stack.push(currentNumber)
          }
    
          for (let number of path.entries()) {
            if (this._generate(map, index + 1)) {
              this.result[index] = currentNumber
              this.yes(path, index)
              return true
            }
          }
    
          return false
        }
    
        generate() {
          if (this._generate(this.map, 0)) {
            this.success = true
          }
    
          this.finalLog()
    
          return this
        }
    
      }
  
    </script>

    <script src="https://cdn.rawgit.com/MaxArt2501/object-observe/master/dist/object-observe-lite.min.js"></script>

    <script>
  
      // Utility
      var utils = (() => {
        function dom (selector) {
          if (selector[0] === '#') {
            return document.getElementById(selector.slice(1))
          }
          return document.querySelectorAll(selector)
        }
        
        function copyJSON (obj) {
          return JSON.parse(JSON.stringify(obj))
        }
        
        function isTouchDevice () {
          return navigator.userAgent
            .match(/(iPhone|iPod|iPad|Android|BlackBerry)/)
        }
        
        function getWorkerURLFromElement(selector) {
          var element = dom(selector)
          var content = babel.transform(element.innerText).code
          var blob = new Blob([content], {type: 'text/javascript'})
          return URL.createObjectURL(blob)
        }
      
      // Will be used for restoring caret positions on rerenders.
      // Taken from:
      // http://stackoverflow.com/questions/1125292/how-to-move-cursor-to-end-of-contenteditable-entity
        var cursorManager = (function () {
          var cursorManager = {}
      
            var voidNodeTags = [
              'AREA', 'BASE', 'BR', 'COL', 'EMBED',
              'HR', 'IMG', 'INPUT', 'KEYGEN', 'LINK',
              'MENUITEM', 'META', 'PARAM', 'SOURCE',
              'TRACK', 'WBR', 'BASEFONT', 'BGSOUND',
              'FRAME', 'ISINDEX'
            ];
      
            Array.prototype.contains = function(obj) {
                var i = this.length;
                while (i--) {
                    if (this[i] === obj) {
                        return true;
                    }
                }
                return false;
            }
      
            function canContainText(node) {
                if(node.nodeType == 1) {
                    return !voidNodeTags.contains(node.nodeName);
                } else {
                    return false;
                }
            };
      
            function getLastChildElement(el){
                var lc = el.lastChild;
                while(lc && lc.nodeType != 1) {
                    if(lc.previousSibling)
                        lc = lc.previousSibling;
                    else
                        break;
                }
                return lc;
            }
            cursorManager.setEndOfContenteditable = function(contentEditableElement) {
      
                while(getLastChildElement(contentEditableElement) &&
                      canContainText(getLastChildElement(contentEditableElement))) {
                    contentEditableElement = getLastChildElement(contentEditableElement);
                }
      
                var range,selection;
                if(document.createRange) {
                    range = document.createRange();
                    range.selectNodeContents(contentEditableElement);
                    range.collapse(false);
                    selection = window.getSelection();
                    selection.removeAllRanges();
                    selection.addRange(range);
                }
                else if(document.selection)
                { 
                    range = document.body.createTextRange();
                    range.moveToElementText(contentEditableElement);
                    range.collapse(false);
                    range.select();
                }
            }
      
            return cursorManager
        })()
        
        return {
          copyJSON, cursorManager, dom,
          getWorkerURLFromElement, isTouchDevice
        }
      })();
      
      
      // API Adapter
      class SudokuAdapter {
        constructor(url) {
          this.worker = new Worker(url)
          return this
        }
        
        _postMessage(options) {
          this.worker.postMessage(JSON.stringify(options))
          return new Promise((resolve, reject) => {
            this.worker.onmessage = event => {
              resolve(event.data)
            }
          })
        }
        
        generate(options) {
          options = Object.assign
            ({}, options, { method: 'generate' })
          
          return this._postMessage(options)
        }
        
        validate(options) {
          options = Object.assign
            ({}, options, { method: 'validate' })
          
          return this._postMessage(options)
        }
      }
      
      
      // Client Side Settings
      const SUDOKU_APP_CONFIG = {
        HINTS: 34,
        TRY_LIMIT: 100000,
        WORKER_URL: utils.getWorkerURLFromElement('#worker'),
        DOM_TARGET: utils.dom('#sudoku-app')
      }
      
      
      // Client Side
      var SudokuApp = (config => {
        const {
          HINTS, TRY_LIMIT,
          WORKER_URL, DOM_TARGET
        } = config
        
        var sudokuAdapter = new SudokuAdapter(WORKER_URL)
        
        var state = {
          success: null,
          board: null,
          solution: null,
          solved: null,
          errors: []
        };
        Object.observe(state, render)
        
        var history = [state]
        var historyStash = []
        
        
        // Event listeners
        var onClickGenerate = initialize
        
        var onClickSolve = function () {
          setState({
            board: state.solution,
            solved: true,
            errors: []
          })
        }
        
        var onKeyUpCell = function (event) {
          var key = event.keyCode
          if (            // a
            key === 36 || // r
            key === 37 || // r
            key === 38 || // o
            key === 39 || // w
            key === 9  || // tab
            // mod key flags are always false in keyup event
            // keyIdentifier doesn't seem to be implemented
            // in all browsers
            key === 17 || // Control
            key === 16 || // Shift
            key === 91 || // Meta
            key === 19 || // Alt
            event.keyIdentifier === 'Control' ||
            event.keyIdentifier === 'Shift'   ||
            event.keyIdentifier === 'Meta'    ||
            event.keyIdentifier === 'Alt'     
          ) return
          
          var cell = event.target
          var value = cell.innerText
          
          if (value.length > 4) {
            cell.innerText = value.slice(0, 4)
            return false
          }
          
          var cellIndex = cell.getAttribute('data-cell-index')
          cellIndex = parseInt(cellIndex, 10)
          var rowIndex = Math.floor(cellIndex / 9)
          var cellIndexInRow = cellIndex - (rowIndex * 9)
          
          var board = Object.assign([], state.board)
          board[rowIndex].splice(cellIndexInRow, 1, value)
          
          validate(board).then(errors => {
            historyStash = []
            history.push({})
            var solved = null
            if (errors.indexOf(true) === -1) {
              solved = true
              board.forEach(row => {
                row.forEach(value => {
                  if (!value || !parseInt(value, 10) || value.length > 1) {
                    solved = false
                  }
                })
              })
            }
            if (solved) {
              board = Object.assign([], board).map(row => row.map(n => +n))
            }
            setState({ board, errors, solved }, (newState) => {
              history[history.length - 1] = newState
              restoreCaretPosition(cellIndex)
            })
          })
        }
        
        function keyDown (event) {
          var keys = {
            ctrlOrCmd: event.ctrlKey || event.metaKey,
            shift: event.shiftKey,
            z: event.keyCode === 90
          }
          
          if (keys.ctrlOrCmd && keys.z) {
            if (keys.shift && historyStash.length) {
              redo()
            } else if (!keys.shift && history.length > 1) {
              undo()
            }
          }
        }
        
        function undo () {
          historyStash.push(history.pop())
          setState(utils.copyJSON(history[history.length - 1]))
        }
        
        function redo () {
          history.push(historyStash.pop())
          setState(utils.copyJSON(history[history.length - 1]))
        }
        
        
        function initialize () {
          unbindEvents()
          render()
          getSudoku().then(sudoku => {
            setState({
              success: sudoku.success,
              board: sudoku.board,
              solution: sudoku.solution,
              errors: [],
              solved: false
            }, newState => {
              history = [newState]
              historyStash = []
            })
          })
        }
        
        function setState(newState, callback) {
          requestAnimationFrame(() => {
            Object.assign(state, newState)
            if (typeof callback === 'function') {
              var param = utils.copyJSON(state)
              requestAnimationFrame(callback.bind(null, param))
            }
          })
        }
        
        function bindEvents() {
          var generateButton = utils.dom('#generate-button')
          var solveButton = utils.dom('#solve-button')
          var undoButton = utils.dom('#undo-button')
          var redoButton = utils.dom('#redo-button')
          generateButton &&
            generateButton
              .addEventListener('click', onClickGenerate)
          solveButton &&
            solveButton
              .addEventListener('click', onClickSolve)
          undoButton &&
            undoButton
              .addEventListener('click', undo)
          redoButton &&
            redoButton
              .addEventListener('click', redo)
          
          var cells = utils.dom('.sudoku__table-cell')
          ;[].forEach.call(cells, (cell) => {
            cell.addEventListener('keyup', onKeyUpCell)
          })
          
          window.addEventListener('keydown', keyDown)
        }
        
        function unbindEvents() {
          var generateButton = utils.dom('#generate-button')
          var solveButton = utils.dom('#solve-button')
          var undoButton = utils.dom('#undo-button')
          var redoButton = utils.dom('#redo-button')
          generateButton &&
            generateButton
              .removeEventListener('click', onClickGenerate)
          solveButton &&
            solveButton
              .removeEventListener('click', onClickSolve)
          undoButton &&
            undoButton
              .removeEventListener('click', undo)
          redoButton &&
            redoButton
              .removeEventListener('click', redo)
          
          var cells = utils.dom('.sudoku__table-cell')
          ;[].forEach.call(cells, (cell) => {
            cell.removeEventListener('keyup', onKeyUpCell)
          })
          
          window.removeEventListener('keydown', keyDown)
        }
        
        function restoreCaretPosition(cellIndex) {
          utils.cursorManager.setEndOfContenteditable(
            utils.dom(`[data-cell-index="${ cellIndex }"]`)[0]
          )
        }
        
        function getSudoku() {
          return sudokuAdapter.generate({
            hints: HINTS,
            limit: TRY_LIMIT
          })
        }
        
        function validate(board) {
          var map = board.reduce((memo, row) => {
            for (let num of row) {
              memo.push(num)
            }
            return memo
          }, []).map((num) => parseInt(num, 10))
          
          var validations = []
          
          // Will validate one by one
          for (let [index, number] of map.entries()) {
            if (!number) {
              validations.push(
                new Promise(res => {
                  res({ result: { box: -1, col: -1, row: -1 } })
                })
              )
            } else {
              let all = Promise.all(validations)
              validations.push(all.then(() => {
                return sudokuAdapter.validate({map, number, index})
              }))
            }
          }
          
          return Promise.all(validations)
            .then(values => {
              var errors = []
              for (let [index, validation] of values.entries()) {
                let { box, col, row } = validation.result
                let errorInBox = box.first !== box.last
                let errorInCol = col.first !== col.last
                let errorInRow = row.first !== row.last
      
                let indexOfRow = Math.floor(index / 9)
                let indexInRow = index - (indexOfRow * 9)
                
                errors[index] = errorInRow || errorInCol || errorInBox
              }
      
              return errors
            })
        }
        
        function render() {
          unbindEvents()
      
          DOM_TARGET.innerHTML = `
            <div class='sudoku'>
              ${ headerComponent() }
              ${ contentComponent() }
            </div>
          `
          
          bindEvents()
        }
        
        function buttonComponent(props) {
          var { id, text, mods, classes } = props
          
          var blockName = 'button'
          var modifiers = {}
          var modType = toString.call(mods)
          if (modType === '[object String]') {
            modifiers[mods] = true
            
          } else if (modType === '[object Array]') {
            for (let modName of mods) {
              modifiers[modName] = true
            }
          }
          
          var blockClasses = bem.makeClassName({
            block: blockName,
            modifiers: modifiers
          });
          
          var buttonTextClass = `${blockName}-text`
          if (Object.keys(modifiers).length) {
            buttonTextClass += (
              Object.keys(modifiers).reduce((memo, curr) => {
                return memo + ` ${blockName}--${curr}-text`
              }, '')
            )
          }
          
          var lgText = typeof text === 'string' ?
              text : text[0]
          var mdText = typeof text === 'string' ?
              text : text[1]
          var smText = typeof text === 'string' ?
              text : text[2]
          
          return (`
            <button
              id='${ id }'
              class='${ blockClasses } ${ classes || "" }'>
              <span class='show-on-sm ${buttonTextClass}'>
                ${ smText }
              </span>
              <span class='show-on-md ${buttonTextClass}'>
                ${ mdText }
              </span>
              <span class='show-on-lg ${buttonTextClass}'>
                ${ lgText }
              </span>
            </button>
          `)
        }
        
        function messageComponent(options) {
          var { state, content } = options
          
          var messageClass = bem.makeClassName({
            block: 'message',
            modifiers: state ? {
              [state]: true
            } : {}
          })
          
          return (`
            <p class='${ messageClass }'>
              ${ content }
            </p>
          `)
        }
        
        function descriptionComponent(options) {
          var { className, infoLevel } = options
          
          var technical = ``
          
          var description = `Sudoku Description. Sudoku is a puzzle that needs little introduction. The object is to put a digit from 1 to N in each cell of the grid so that every row, column, and bold region contains each digit once, where N is the number of rows and columns in the puzzle. (Usually N = 9.).`
          
          if (infoLevel === 'full') {
            return (`
              <p class='${ className || '' }'>
                ${ technical } ${ description }
              </p>
            `)
            
          } else if (infoLevel === 'mini') {
            return (`
              <p class='${ className || '' }'>
                ${ description }
              </p>
            `)
          }
        }
        
        function restoreScrollPosComponent() {
          return `<div style='height: 540px'></div>`
        }
        
        function headerComponent() {
          return (`
            <div class='sudoku__header'>
      
              <h1 class='sudoku__title'>
      
                <span class='show-on-sm'>
                  Sudoku Puzzle
                </span>
      
                <span class='show-on-md'>
                  Sudoku Puzzle
                </span>
      
                <span class='show-on-lg'>
                  Sudoku Puzzle
                </span>
      
              </h1>
      
              ${descriptionComponent({
                infoLevel: 'mini',
                className: 'sudoku__description show-on-md'
              })}
      
              ${descriptionComponent({
                infoLevel: 'full',
                className: 'sudoku__description show-on-lg'
              })}
      
              ${
                  state.success ? (`
          
                    ${buttonComponent({
                      id: 'generate-button',
                      text: ['New Board', 'New Board', 'New'],
                      mods: 'primary'
                    })}
          
                    ${ state.solved ?
                        buttonComponent({
                          id: 'solve-button',
                          text: 'Solved',
                          mods: ['tertiary', 'muted']
                        }) :
                        buttonComponent({
                          id: 'solve-button',
                          text: 'Solve',
                          mods: 'secondary'
                        })
                    }
      
                  `)
                  
                  : (`
          
                    ${buttonComponent({
                      id: 'generate-button',
                      text: ['Generating', '', ''],
                      mods: ['disabled', 'loading']
                    })}
          
                    ${buttonComponent({
                      id: 'solve-button',
                      text: 'Solve',
                      mods: 'disabled'
                    })}
                  `)
                  
              }
      
              ${ utils.isTouchDevice() ? (`
      
                ${buttonComponent({
                  id: 'redo-button',
                  text: ['&raquo;', '&raquo;', '&gt;', '&gt;'],
                  classes: 'fr',
                  mods: [
                    'neutral',
                    'compound',
                    'compound-last',
                    `${ !historyStash.length ?
                    'disabled' :
                    ''
                    }`
                  ]
                })}
                ${buttonComponent({
                  id: 'undo-button',
                  text: ['&laquo;', '&laquo;', '&lt;', '&lt;'],
                  classes: 'fr',
                  mods: [
                    'neutral',
                    'compound',
                    'compound-first',
                    `${ history.length > 1 ?
                    '' :
                    'disabled'
                    }`
                  ]
                })}
      
            `) : ''}
      
            </div>
          `)
        }
        
        function contentComponent() {
          var _isSeparator = (index) =>
            !!index && !((index + 1) % 3)
          
          var resultReady = !!state.board
          var fail = resultReady && !state.success
          
          if (!resultReady) {
            return (`
              ${messageComponent({
                state: 'busy',
                content: `Generating new board...`
              })}
              ${ restoreScrollPosComponent() }
            `)
          }
          
          if (fail) {
            return (`
              ${messageComponent({
                state: 'fail',
                content: `Something went wrong with this board, try generating another one.`
              })}
              ${ restoreScrollPosComponent() }
            `)
          }
          
          var rows = state.board
          
          return (`
            <table class='sudoku__table'>
      
              ${rows.map((row, index) => {
                let className = bem.makeClassName({
                  block: 'sudoku',
                  element: 'table-row',
                  modifiers: {
                    separator: _isSeparator(index)
                  }
                });
      
                return (
                  `<tr class='${ className }'>
      
                    ${row.map((num, _index) => {
                      let cellIndex = (index * 9) + _index
                      let separator = _isSeparator(_index)
                      let editable = typeof num !== 'number'
                      let error = state.errors[cellIndex]
                      let className = bem.makeClassName({
                        block: 'sudoku',
                        element: 'table-cell',
                        modifiers: {
                          separator,
                          editable,
                          error,
                          'editable-error': editable && error
                        }
                      });
      
                      return (
                        `\n\t
                        <td class='${ className }'
                            data-cell-index='${ cellIndex }'
                            ${ editable ? 'contenteditable' : ''}>
                              ${ num }
                        </td>`
                      )
                    }).join('')}
      
                  \n</tr>\n`
                )
      
              }).join('')}
      
            </table>
          `)
        }
        
        return { initialize }
        
      })( SUDOKU_APP_CONFIG ).initialize()
  
    </script>

  @endif
  
  @if($game -> name == 'Look for Mines')
  
    <script>
    
      var demo = document.getElementById("demo"),
      matrix = document.getElementById("matrix"),
      base = document.getElementById("base"),
      tilesH = 10;
  		tilesV = 10,
      numMines = 20,
      tilesLeft = 90,
      iState = 0,
      cont = 0;
      mat = [],
      states = [],
      mines = [],
      hasMine = [],
      CLOSE = 0,
  		OPEN = 1,
      FLAG = 2,
      QUESTION = 3,
      MINE = 4,
      X = 0,
      Y = 1,
      LEFT = 0;
  		RIGHT = 1;
  		TRUE = 1;
  		FALSE = 0;
      inGame = true;
  
      // column 0: LeftClick 1: RightClick
      automaton = [[1,2], //0: Close
                   [1,1], //1: Open
                   [2,3], //2: Flag
                   [1,0], //3: Question
                   ];
  
      function Initialize(){
        mat = NewMatrix();
        for(var i = 0; i < tilesH; i++){
          states[i] = [];
          hasMine[i] = [];
          for(var j = 0; j < tilesV; j++){
            states[i][j] = CLOSE;
            hasMine[i][j] = FALSE;
          }
        }
        for(var i = 0; i < numMines; i++)
        	PlaceMine(tilesH, tilesV);
      }  
      
      function NewMatrix(){
        var matrix = [];
        for (var i = 0; i < tilesH; i++){
          matrix[i] = [];
        	for (var j = 0; j < tilesV; j++)
            matrix[i][j] = NewItem(i, j, "close");
        }
        return matrix;
      }
      
      function NewItem(x, y, type){
        var item = document.createElement("div");
        item.onclick = function(){Update(LEFT, x, y)};
        item.oncontextmenu = function(){
      		Update(RIGHT, x, y);
          return false;
        };
        item.className = type;
        matrix.appendChild(item);
        return item;
      }
      
      function Update(click, x, y){
        var aState = states[x][y];
        
        if (inGame){
          if(hasMine[x][y] && click == LEFT){
            DisplayMines();
            demo.innerHTML = "Perdiste";
            inGame = false;
          }
          
          else{
            var newState = states[x][y] = automaton[aState][click];
            DisplayState(mat[x][y], newState);
            if(newState == OPEN && newState != aState){
              Open(x, y);
              ShowMatrix(states, tilesH, tilesV);
            }
          }
        }  
      }
      
      function ShowMatrix(arr, vLimit, hLimit){
        demo.innerHTML = "";
        for(var i = 0; i < hLimit; i++){
          for(var j = 0; j < vLimit; j++){
            demo.innerHTML += arr[i][j];
          }
          demo.innerHTML += "</br>";
        }
      }
      
      function PlaceMine(xLimit, yLimit){
        var x, y;
        do{
        	x = Math.floor(Math.random() * xLimit)
        	y = Math.floor(Math.random() * yLimit)
        }while(states[x][y] != CLOSE);
        hasMine[x][y] = TRUE;
        mines[cont] = [];
        mines[cont][X] = x;
        mines[cont][Y] = y;
        cont++;
      }
      
      function DisplayMines(){
        var x, y;
        for (var i = 0; i < numMines; i++){
          var x = mines[i][X];
          var y = mines[i][Y];
          mat[x][y].className = "mine";
        }
      }
      
      function DisplayState(item, state){
        switch (state){
          case CLOSE:
            item.className = "close";
            break;
          case OPEN:
            item.className = "open";
            break;
          case FLAG:
            item.className = "flag";
            break;
          case QUESTION:
            item.className = "question";
            break;
        }
      }
      
      function GetNeighbours(x, y, xLimit, yLimit){
        var cont = 0, neighbours = [];
        for(var i = -1; i<=1; i++){
          for(var j = -1; j<=1; j++){
            if (j == 0 && i == 0) continue;
            var _x = x+i, _y = y+j;
            if(_x >= 0 && _y >= 0 && _x < xLimit && _y < yLimit){
              neighbours[cont] = [];
              neighbours[cont][X] = _x;
              neighbours[cont][Y] = _y;
              cont++;
            }
          }
        }
        return neighbours;
      }
      
      function CountMines(neighbours){
        var cont = 0, x = 0, y = 0;
        for (var i = 0; i < neighbours.length; i++){
          x = neighbours[i][X];
          y = neighbours[i][Y];
          if (hasMine[x][y]) cont++;
        }
        return cont;
      }
      
      function DisplayQueue(queue){
        demo.innerHTML = "";
        for(var i = 0; i < queue.length; i+=2){
          demo.innerHTML += "("+queue[i]+","+queue[i+1]+")";
        }
      }
      
      function Open(x, y){
        var queue = [], conti = "yes";
        queue.push(x);
        queue.push(y);
        
        while(queue.length > 0 && conti == "yes"){
        	var _x = queue.shift();
          var _y = queue.shift();
         	
          var neighbours = GetNeighbours(_x, _y, tilesH, tilesV);
          var nMines = CountMines(neighbours);
          if (nMines == 0){
            for (var i = 0; i < neighbours.length; i++){
              var nX = neighbours[i][X];
              var nY = neighbours[i][Y];
              if(states[nX][nY] == CLOSE){
                queue.push(nX);
                queue.push(nY);
              }
            }
          }
          else{
            mat[_x][_y].innerHTML = nMines;
          }
          states[_x][_y] = OPEN;
          DisplayState(mat[_x][_y], OPEN);
        }
      }
      
      /*function Open(x, y){
        var queue = [], conti = "yes";
        queue.push(x);         
        queue.push(y);         
        
        do{
          var _x = queue.shift();
          var _y = queue.shift();
          
          var neighbours = GetNeighbours(_x, _y, tilesH, tilesV);
          var nMines = CountMines(neighbours);
      		alert("hola");
          if (nMines == 0){ 
            for (var i = 0; i < neighbours.length; i++){
              var nX = neighbours[i][X];
              var nY = neighbours[i][Y];
            	if (states[nX][nY] == CLOSE){
                queue.push(nX);         
        				queue.push(nY);
              }
            }
            states[_x][_y] = OPEN; 
          	DisplayState(mat[nX][nY], OPEN);
          }
          else{
            states[_x][_y] = OPEN; 
          	DisplayState(mat[nX][nY], OPEN);
            mat[_x][_y].innerHTML = nMines;
          }
          conti = prompt("Continue?", "yes");
          DisplayQueue(queue);
        }while(queue.length > 0 && conti == "yes");
      }*/
      
      function Display(text){
        demo.innerHTML += "</br>" + text;
      }
      
      Initialize();
      //ShowMatrix(hasMine, tilesH, tilesV);
      DisplayMines();
      
    </script>
  
  @endif
  
  <script>

    DecoupledEditor
            
      .create(document.querySelector('#editor'))
            
      .then( editor => {
                
        const toolbarContainer = document.querySelector('#toolbar-container');
        toolbarContainer.appendChild( editor.ui.view.toolbar.element );
            
      })
            
      .catch( error => {
                
        console.error(error);
        
      });

  </script>

  <script>

    var jq=jQuery.noConflict();
    
    jq(document).ready( function(){
      
      jq(document).keydown(function(event){

        var content = document.getElementById("editor").children;
        var contentCount = document.getElementById("editor").childElementCount;

        jq(document).ready(function($){

          $("#text").empty();

          var allText = "";

          for(var i = 0; i < contentCount; i++)
          {
            allText = allText + content[i].outerHTML;
          }

          var text = $('#text');

          text.val(allText);

        });
        
      });
      
      jq(document).mousedown(function(event){

        var content = document.getElementById("editor").children;
        var contentCount = document.getElementById("editor").childElementCount;

        jq(document).ready(function($){

          $("#text").empty();

          var allText = "";

          for(var i = 0; i < contentCount; i++)
          {
            allText = allText + content[i].outerHTML;
          }

          var text = $('#text');

          text.val(allText);

        });
        
      });
    
    });

  </script>
  
  <script>

    DecoupledEditor
            
      .create(document.querySelector('#editor_i'))
            
      .then( editor => {
                
        const toolbarContainer = document.querySelector('#toolbar-container-i');
        toolbarContainer.appendChild( editor.ui.view.toolbar.element );
            
      })
            
      .catch( error => {
                
        console.error(error);
        
      });

  </script>

  <script>

    var jq=jQuery.noConflict();
    
    jq(document).ready( function(){
      
      jq(document).keydown(function(event){

        var content = document.getElementById("editor_i").children;
        var contentCount = document.getElementById("editor_i").childElementCount;

        jq(document).ready(function($){

          $("#text_i").empty();

          var allText = "";

          for(var i = 0; i < contentCount; i++)
          {
            allText = allText + content[i].outerHTML;
          }

          var text = $('#text_i');

          text.val(allText);

        });
        
      });
      
      jq(document).mousedown(function(event){

        var content = document.getElementById("editor_i").children;
        var contentCount = document.getElementById("editor_i").childElementCount;

        jq(document).ready(function($){

          $("#text_i").empty();

          var allText = "";

          for(var i = 0; i < contentCount; i++)
          {
            allText = allText + content[i].outerHTML;
          }

          var text = $('#text_i');

          text.val(allText);

        });
        
      });
    
    });

  </script>

@endsection

@section('content')
    
  <section class="content-header">
        
    <h1> {!! $game -> name !!} </h1>
    
  </section>
    
  <div class="content">
        
    <div class="box box-primary">
            
      <div class="box-body">
                
        <div class="row">
                    
          @include('games.show_fields')
                    
          <a href="{!! route('games.index') !!}" class="btn btn-default" style="margin-left: 20px;">Back</a>
          
        </div>
            
      </div>
        
    </div>
    
  </div>
  
  <aside class="control-sidebar control-sidebar-dark control-sidebar-close" style="background: rgba(0,0,0,0.8);">
  
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      
      <li class="active">
        
        <a href="#games" data-toggle="tab">
        
          <i class="fa fa-book"></i>
        
        </a>
        
      </li>
      
      <li>
        
        <a href="#game_views" data-toggle="tab">
        
          <i class="fa fa-eye"></i>
        
        </a>
        
      </li>
      
      <li>
        
        <a href="#game_updates" data-toggle="tab">
        
          <i class="fa fa-edit"></i>
        
        </a>
        
      </li>
    
    </ul>
    
    <div class="tab-content" style="padding: 20px;">
    
      <div class="tab-pane active" id="games">
        
        <h3 class="control-sidebar-heading" style="margin-top: 0; border-bottom: 1px solid rgba(255,255,255,0.3);"> Games </h3>
        
        <ul class="control-sidebar-menu">
          
          @foreach($games as $game)
            
              <li>
                
                <a href="{!! route('games.show', [$game -> id]) !!}">
                  
                  <i class="menu-icon fa fa-book bg-light-blue"></i>
    
                  <div class="menu-info">
                    
                    <h4 class="control-sidebar-subheading"> {!! $game -> name !!} </h4>
                    <p> {!! $game -> created_at !!} </p>
                  
                  </div>
                
                </a>
              
              </li>
            
          @endforeach
        
        </ul>

      </div>
      
      <div class="tab-pane" id="game_views">
        
        <h3 class="control-sidebar-heading" style="margin-top: 0; border-bottom: 1px solid rgba(255,255,255,0.3);"> Game Views </h3>
        
        <ul class="control-sidebar-menu">
          
          @foreach($gameViewsList as $gameViewList)
            
              <li>
                
                <a href="">
                  
                  <i class="menu-icon fa fa-eye bg-light-blue"></i>
    
                  <div class="menu-info">
                    
                    <h4 class="control-sidebar-subheading"> {!! $gameViewList -> name !!} </h4>
                    <p> {!! $gameViewList -> datetime !!} </p>
                  
                  </div>
                
                </a>
              
              </li>
            
          @endforeach
        
        </ul>

      </div>
      
      <div class="tab-pane" id="game_updates">
        
        <h3 class="control-sidebar-heading" style="margin-top: 0; border-bottom: 1px solid rgba(255,255,255,0.3);"> Game Updates </h3>
        
        <ul class="control-sidebar-menu">
          
          @foreach($gameUpdatesList as $gameUpdateList)
            
              <li>
                
                <a href="">
                  
                  <i class="menu-icon fa fa-edit bg-light-blue"></i>
    
                  <div class="menu-info">
                    
                    <h4 class="control-sidebar-subheading"> {!! $gameUpdateList -> name !!} </h4>
                    <p> {!! $gameUpdateList -> datetime !!} </p>
                  
                  </div>
                
                </a>
              
              </li>
            
          @endforeach
        
        </ul>

      </div>
      
    </div>
    
  </aside>

@endsection