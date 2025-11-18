<IfModule mod_rewrite.c>
    RewriteEngine On

    # Diretório base do projeto (EXATO)
    RewriteBase /encomendas-do-chef---gestor/

    # Se arquivo ou pasta existir, entrega normal
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d

    # Tudo para o index.php
    RewriteRule ^(.*)$ index.php [QSA,L]
</IfModule>

# Proteger arquivos sensíveis
<FilesMatch "^(\.env|composer\.json|composer\.lock|\.htaccess)$">
    Require all denied
</FilesMatch>

Options -Indexes
