# Driver Master

Loja online de eletrónicos desenvolvida no âmbito da unidade curricular 605 – Programar para a Web (vertente servidor). Permite a qualquer visitante consultar e filtrar produtos por categoria sem autenticação; para comprar, o utilizador precisa de criar conta e fazer login, podendo gerir o carrinho e finalizar encomendas. Os administradores dispõem de um painel de gestão completo.

## Funcionalidades

**Loja (público / clientes)**
- Consulta e filtragem de produtos por categoria, sem necessidade de login
- Registo e autenticação de utilizadores
- Carrinho de compras: adicionar produtos, ajustar quantidades, remover artigos
- Finalização de compra, com decremento automático do stock
- Histórico de encomendas na área pessoal do cliente

**Administração**
- Gestão de produtos (criar, editar, eliminar)
- Gestão de categorias
- Consulta de todas as encomendas realizadas
- Gestão de utilizadores registados

## Tecnologias

- **Frontend**: HTML + CSS
- **Backend**: PHP (server-side), com sessões para autenticação
- **Base de dados**: MySQL (`b15_41556107_DriverMaster.sql`)
- **Segurança**: passwords encriptadas com bcrypt (`password_hash` / `password_verify`)
- Hospedagem de demonstração em byethost15 (servidor MySQL remoto)

## Estrutura do projeto

```
htdocs/
├── index.php                  # Redireciona para loja/index.php
├── setup.php                   # Cria utilizadores de teste (admin + 2 clientes)
├── style.css / loja.css
├── loja/
│   ├── index.php
│   ├── produtos.php
│   ├── detalhe.php
│   ├── carrinho.php
│   ├── finalizar.php
│   └── encomendas.php
├── admin/
│   ├── index.php
│   ├── produtos.php
│   ├── produtos_novo.php
│   ├── produtos_editar.php
│   ├── produtos_eliminar.php
│   ├── categorias.php
│   ├── encomendas.php
│   └── utilizadores.php
├── login/
│   ├── login.php
│   ├── registo.php
│   ├── autenticar.php
│   └── logout.php
├── includes/
│   ├── ligacao.php               # Ligação à base de dados
│   ├── navbar_loja.php
│   ├── navbar_admin.php
│   ├── auth.php                   # Proteção de páginas de cliente
│   └── auth_admin.php             # Proteção do painel de administração
├── imagens/ e imagensloja/
└── .htaccess
```

## Base de dados

Tabelas principais definidas em `b15_41556107_DriverMaster.sql`:

- `utilizadores` — id, nome, email, senha (hash), tipo (`cliente` | `admin`)
- `categorias` — id, nome
- `produtos` — id, nome, descrição, preço, stock, categoria_id, imagem
- `carrinho` — id, user_id, produto_id, quantidade, comprado, data

## Como correr o projeto

1. Colocar a pasta `htdocs/` num servidor Apache com PHP e MySQL (por exemplo Wampserver/XAMPP).
2. Criar a base de dados e importar o esquema de `b15_41556107_DriverMaster.sql`.
3. Editar `includes/ligacao.php` com as credenciais do teu servidor MySQL local (o ficheiro incluído aponta por omissão para o servidor de hospedagem remoto usado na demonstração — substitui `$servername`, `$username`, `$password` e `$dbname` pelos teus).
4. Correr `setup.php` uma vez para criar os utilizadores de teste (administrador e dois clientes).
5. Aceder a `index.php`, que redireciona automaticamente para `loja/index.php`.

## Contas de teste

| Tipo | Email | Password |
|---|---|---|
| Administrador | admin@loja.com | admin |
| Cliente | diogo@gmail.com | diogodias |
| Cliente | maria@gmail.com | mariaribeiro |

> Estas credenciais são apenas para o ambiente de demonstração/teste e são criadas pelo `setup.php`.

## Dificuldades encontradas e soluções

- **Contagem de encomendas incorreta** — o painel de admin contava produtos individuais em vez de encomendas; resolvido agrupando os registos do carrinho por `COUNT(DISTINCT DATE_FORMAT(c.data, '%Y-%m-%d %H:%i'))`.
- **Autenticação** — criados `auth.php` e `auth_admin.php` para proteger, respetivamente, páginas de cliente e o painel de administração.
- **Stock não atualizado após compra** — adicionada query em `finalizar.php` para decrementar o stock antes de marcar os itens do carrinho como `comprado=1`.
- **Caminhos relativos no painel de administração** — como o admin está numa subpasta diferente da loja, foi criada a variável `$depth_admin` para ajustar automaticamente os caminhos relativos a CSS, imagens e includes.

## Documentação adicional

Relatório completo em `Relatório Driver Master.pdf` (introdução, objetivos, estrutura da base de dados, mapa de navegação e dificuldades encontradas), e vídeo de demonstração em `Projeto site.mp4`.
