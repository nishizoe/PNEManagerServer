set :application, "pms"
set :domain,      "pne.cc"
set :deploy_to,   "/opt/sabakan/#{domain}"
set :app_path,    "app"

set :scm,         :git
set :repository,  "gitolite@localhost:#{application}.git"
set :deploy_via,  :copy

set :model_manager, "doctrine"
# Or: `propel`
set :shared_files,      ["app/config/parameters.ini", "web/.htaccess", "src/Deploy/HtmlBundle/Resources/config/routing.yml", "src/PMS/ApiBundle/Controller/Listener/RequestListener.php"]
set :shared_children,     [app_path + "/cache", app_path + "/logs", web_path + "/uploads", "vendor"]
set :update_vendors, true
set :cache_warmup, false

role :web,        domain                         # Your HTTP server, Apache/etc
role :app,        domain                         # This may be the same as your `Web` server
role :db,         domain, :primary => true       # This is where Rails migrations will run

set  :use_sudo,       false
set  :keep_releases,  3
