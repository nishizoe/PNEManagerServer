set :application, "pms"
set :domain,      "pne.cc"
set :deploy_to,   "/var/www/sns/#{domain}.deploy"
set :app_path,    "app"

set :scm,         :git
set :repository,  "gitolite@localhost:#{application}.git"
set :deploy_via,  :copy

set :model_manager, "doctrine"
# Or: `propel`
set :shared_files,      ["app/config/parameters.ini", "web/.htaccess"]
set :shared_children,     [app_path + "/cache", app_path + "/logs", web_path + "/uploads", "vendor"]
set :update_vendors, true
set :deploy_via, :rsync_with_remote_cache

role :web,        domain                         # Your HTTP server, Apache/etc
role :app,        domain                         # This may be the same as your `Web` server
role :db,         domain, :primary => true       # This is where Rails migrations will run

set  :use_sudo,       false
set  :keep_releases,  3
