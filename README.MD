# Console Calculator  

## Disclaimer: 
This is just a test task, so don't treat it like a ready-to-use product!
If you want to parse your language, create your grammar using EBNF, use Bison as the parser, and execute the AST result tree with a mature interpreter or backend compiler.

### Task  
You need to create a Symfony Console application to calculate a math expression.
Please avoid using `eval` to obtain the result.

### Capabilities
 * Basic math operations: addition (`+`), subtraction (`-`), division (`/`), multiplication (`*`), unary minus (`~`), and parentheses (`()`) for precedence.
 * Mathematical constants: `e` and `pi`.
 * Functions: `sin`, `cos`, and `pow`.
 * You can easily add your own operators, constants, and functions.
It can evaluate expressions such as: `2 * pi + pow(2, 4 + 4)`.

### How it works
The input is split into tokens by the Lexer.
These tokens are then processed using the simple shunting yard algorithm to convert them into Reverse Polish Notation, which is subsequently evaluated.

### How to run
 0. Navigate to the project directory.
 1. Create a Docker container by running: `./docker/docker.sh init`.
 2. Execute your expression with: `./docker/cli.sh calc "2 + 2"`.
 3. Modify files as needed; changes will automatically reflect in the container with the correct permissions.
 4. Enjoy debugging with xDebug inside the container!

### What about the tests?
We've got 100% coverage.
Run them using `./docker/test.sh`.  
The report will be generated in the `./build/` directory.
