# Expert Framework Database

O **Expert Framework Database** é uma biblioteca PHP criada para simplificar a interação com bancos de dados. Ele oferece um construtor de consultas fluente e encadeável para facilitar a realização de operações de banco de dados com alta legibilidade do código.

## Instalação 🚀

Para instalar o Componente Expert Framework Database, você pode usar o Composer. Basta executar o comando abaixo:

```bash
composer require expert-framework/database
```

## Uso ✅
A classe Database oferece uma variedade de métodos para interagir com o banco de dados:

* table(string $table): Especifica a tabela do banco de dados que será consultada.

* begin(): Inicia uma nova transação de banco de dados.

* commit(): Confirma a transação de banco de dados atual.

* rollback(): Desfaz a transação de banco de dados atual.

* insert(?array $fields = []): Insere dados na tabela especificada. Pode incluir um array opcional de campos e valores a serem inseridos.

* insertGetId(?array $fields = []): Insere dados na tabela e retorna o último ID inserido.

* update(?array $fields = []): Atualiza registros na tabela com campos e valores opcionais.

* select(?array $fields = []): Seleciona as colunas a serem recuperadas da tabela.

* where(string $column, string $operator, string|float|int $value, ?string $boolean = 'and'): Adiciona uma cláusula WHERE à consulta.

* join(string $table, string $first, string $operator, string $second, ?string $type = 'JOIN'): Realiza uma operação de JOIN com a tabela especificada e as condições fornecidas.

* delete(): Exclui registros da tabela com base nas condições previamente aplicadas.

* get(): Executa a consulta e retorna o resultado como um array.

### Exemplos

```php
use ExpertFramework\Database\Database;

$data = Database::table('users')
    ->select(['id', 'username', 'email'])
    ->where('status', '=', 'ativo')
    ->get();

Database::table('users')
    ->insert(['username' => 'john_doe', 'email' => 'john@example.com']);

Database::table('users')
    ->where('id', '=', 1)
    ->update(['email' => 'novo_email@example.com']);
```

## Dúvidas 🤔
Caso exista alguma dúvida sobre como instalar, utilizar ou gerenciar o projeto, entre em contato com o email: jonasdasilvaelias@gmail.com

Um grande abraço!
