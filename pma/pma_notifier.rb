require 'rubygems'

require 'net/https'
require 'json'
require 'syslog'

pmshost = 'pne.jp'
pmahost = `hostname`.chop

Syslog.open();
Syslog.info('start pma_notifier');
p "start pma_notifer"
if File.exist?("/tmp/.pmalock") then
  Syslog.info('still run ');
  p "still runn"
  Syslog.info('exit pma_notifier');
  p "exit pma_notifier"
  exit;
end

installDomains = Dir::entries('/var/www/sns/') - ['.', '..']

https = Net::HTTP.new(pmshost, 443)
https.use_ssl = true
https.ca_file = '/opt/sabakan/hosting/script/pma/ca-bundle.crt'
https.verify_mode = OpenSSL::SSL::VERIFY_PEER
https.verify_depth = 5

res = https.request_post('/api/server/update', 'host='+pmahost+'&domain='+installDomains.to_json)

puts res.body

Syslog.info('end pma_notifier');
p "end pma_notifier"
