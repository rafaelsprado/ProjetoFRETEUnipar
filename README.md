# Sistema de Gestão Logística - Trabalho UNIPAR EAD

Este repositório contém a implementação da **Etapa 2** da atividade prática da disciplina de **Fundamentos de Programação para Internet**.

## 📌 Funcionalidades Desenvolvidas
- **Cadastro de Produtos:** Registro de código, descrição, espécie, peso e valor [cite: 45].
- **Cadastro de Endereços:** Registro de CEP, logradouro, número, bairro, município e UF [cite: 43].
- **Cadastro de Transportadoras:** Registro de código, CNPJ, razão social, nome fantasia e endereço [cite: 46].
- **Geração de Fretes (1 para Muitos):** Associação de uma transportadora a múltiplos produtos e endereços de origem/destino [cite: 44].
- **Relatório Diário:** Consulta e exportação em formato CSV dos fretes realizados em determinada data [cite: 47].

## 🚀 Tecnologias Utilizadas
- **Backend:** PHP (PDO / MySQL)
- **Frontend:** HTML5, CSS3, JavaScript e Bootstrap 5
- **Banco de Dados:** MySQL (XAMPP / phpMyAdmin)
- **Controle de Versão:** Git e GitHub

## 📂 Como Executar o Projeto
1. Extraia o código no diretório `C:\xampp\htdocs\logistica`.
2. Inicie os módulos **Apache** e **MySQL** no XAMPP Control Panel.
3. Importe o arquivo `schema.sql` no phpMyAdmin (`http://localhost/phpmyadmin`).
4. Acesse no navegador: `http://localhost/logistica/`
