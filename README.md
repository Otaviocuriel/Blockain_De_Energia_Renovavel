
# Commit-TCC (TCC) — versão para entrega

Este repositório contém uma cópia do projeto Laravel usado no TCC com pequenas
melhorias visuais nas telas de autenticação (registro/login) e um fallback que
permite visualizar o layout sem precisar executar o Vite durante o desenvolvimento.

Principais mudanças neste commit
- Tema escuro para telas de registro e login.
- Fundo com gradiente escuro e ponto de luz suave na página de autenticação.
- Toggle Pessoa / Empresa no formulário de registro (altera campos CPF/CNPJ).
- Versões das views que usam CDN (Tailwind + Alpine) como fallback para facilitar
	testes locais sem rodar o pipeline do frontend.

Como rodar localmente (rápido)
1. Instale dependências PHP: `composer install`.
2. Copie e configure o arquivo de ambiente: `cp .env.example .env` e ajuste as variáveis.
3. (Opcional) Instale dependências JS se for trabalhar com Vite: `npm install`.
4. Gere a chave da aplicação: `php artisan key:generate`.
5. Rode o servidor: `php artisan serve`.

Observação sobre o CSS/JS
- O repositório usa Vite normalmente (arquivo `resources/css/app.css` etc.).
- Para evitar ter que rodar `npm run dev` só para ver o formulário, algumas
	views de autenticação (ex.: `resources/views/auth/register.blade.php`) têm uma
	versão que carrega Tailwind e Alpine via CDN. Isso funciona como fallback e
	facilita a visualização imediata das mudanças de layout.

O que mais foi enviado
- Arquivos principais das views de login/registro atualizados.
- Arquivo `.gitignore` e este `README.md` foram adicionados ao repositório.

Links
- Repositório remoto: https://github.com/Otaviocuriel/Commit_tcc_laravel

Próximos passos sugeridos
- Revisar as outras views e, se desejar, uniformizar o uso do CDN apenas em
	desenvolvimento (adicionar checagem ambiente e uso condicional).
- Opcional: executar `npm run dev` para compilar os assets via Vite e remover o
	fallback CDN quando estiver pronto para produção.

Licença
Este projeto segue a licença MIT (ver `LICENSE` se necessário).

------
Arquivo gerado/atualizado automaticamente durante a preparação do envio para o
GitHub. Se quiser que eu faça um release (v1.0.0) automaticamente, diga e eu crio.
The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
