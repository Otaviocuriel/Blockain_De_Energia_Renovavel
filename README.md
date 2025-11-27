<p align="center">
    <a href="https://laravel.com" target="_blank">
        <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
    </a>
</p>

<p align="center">
    <b>Blockchain de Energia Renovável</b><br>
    Plataforma acadêmica desenvolvida com Laravel para demonstrar a aplicação de blockchain no setor de energia limpa.
</p>

---

## 📚 Sobre o Projeto

Este projeto demonstra uma arquitetura moderna utilizando **Laravel**, com foco em registrar, organizar e simular dados relacionados ao setor de energia renovável. A ideia é mostrar como conceitos de **blockchain**, descentralização e confiabilidade podem ser aplicados academicamente.

---

## ✨ Recursos Principais

* Estrutura em Laravel moderna e organizada.
* Sistema de cadastro e autenticação.
* Interface responsiva com Tailwind CSS.
* Módulo simulado de registro baseado em blockchain.
* Padrões de organização inspirados em projetos oficiais.

---

## 🛠️ Requisitos

* PHP 8.1+
* Composer
* NPM
* MySQL
* Extensões comuns do Laravel habilitadas

---

## 🚀 Instalação

```bash
git clone https://github.com/Otaviocuriel/Blockain_De_Energia_Renovavel.git
cd Blockain_De_Energia_Renovavel

composer install
npm install

cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run dev
php artisan serve
```

Acesse o projeto em: [http://127.0.0.1:8000](http://127.0.0.1:8000)

---

## 📁 Estrutura

Este repositório segue a filosofia oficial do Laravel:

* **app/** – núcleo da aplicação
* **routes/** – rotas web e API
* **resources/** – Blade, Tailwind e assets
* **database/** – migrações e seeds
* **public/** – ponto público e build do Vite

---

## 🔒 Segurança

Certifique-se de remover o `.env` do repositório:

```bash
git rm --cached .env
echo ".env" >> .gitignore
```

---

## 🤝 Contribuindo

Contribuições são bem-vindas! Siga o padrão:

1. Crie uma nova branch
2. Desenvolva a melhoria ou correção
3. Envie seu pull request

---

<p align="center">
    Desenvolvido com ❤️ usando Laravel
</p>
