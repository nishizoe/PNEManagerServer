
require 'rubygems'

require 'net/http'
require 'json'
require 'syslog'
require 'fileutils'

def shellesc(str)
  str.gsub!(/[\!\"\$\&\'\(\)\*\,\:\;\<\=\>\?\[\\\]\^\`\{\|\}\t ]/, '\\\\\\&')
  str
end

Syslog.open()
Syslog.info('start pma')
p "start pma"
if File.exist?("/tmp/.pmalock") then
  Syslog.info('still run ')
  p "still run"
  Syslog.info('exit pma')
  p "exit pma"
  exit
end

FileUtils.touch('/tmp/.pmalock')

pmshost = 'api.pne.cc'
pmahost = `hostname`.chop

installDomains = Dir::entries('/var/www/sns/') - ['.', '..', 'munin.example.com', 'stopped', 'stoppedSNS']
# install or delete snss
Net::HTTP.start(pmshost) { |http|
  if response.code == '200' then
    req = Net::HTTP::Get.new('/api/server/detail?host='+pmahost)
    response = http.request(req)
    details =  JSON.parse(response.body)
    p "server detail"
  
    if details != [] then
      expectedDomains = details['domain']
  
      installTargetDomains = expectedDomains - installDomains
      p installTargetDomains
      installTargetDomains.each { |domain|
        domain = shellesc(domain)
        p "install " + domain
        req = Net::HTTP::Get.new('/api/sns/detail?domain='+domain)
        snsResponse = http.request(req)
        p "sns detail"
        p snsResponse.body
        if snsResponse.code == '200' then
          snsDetail = JSON.parse(snsResponse.body)
          adminEmail = shellesc(snsDetail['adminEmail'])
          p adminEmail
          userResult = ""
          adminResult = ""
          IO.popen('/opt/sabakan/autoinst/install.sh '+domain+' '+adminEmail) do |io|
            while line = io.gets
              userResult = line.split(" ")[0]
              adminResult = line.split(" ")[1]
            end
            if userResult == nil || adminResult == nil then
              p "fail to install " + domain
              IO.popen('/opt/sabakan/autoinst/sns_delete.sh '+domain) do |io|
                while line = io.gets
                end
              end
            else
              req = Net::HTTP::Post.new('/api/sns/setpass')
              req.set_form_data({:domain=>domain,:mpass=>userResult,:apass=>adminResult})
              http.request(req)
              break # take once time to install
            end
          end
        else
          p "fail to install " + domain
        end
      }
  
      deleteTargetDomains = installDomains - expectedDomains
      deleteTargetDomains.each { |domain|
        req = Net::HTTP:Get.new('/api/sns/detail?domain='+domain)
        snsResponse = http.request(req)
        snsDetail = JSON.parse(snsResponse.body)
        if snsDetail['status'] == 'deleted' then
          IO.popen('/opt/sabakan/autoinst/sns_delete.sh ' + domain) do |io|
          end
          p "sns delete"
        else
          p "fail to delete " + domain

          http.request_post('/api/server/event', 'eventType=error&message=deletefailed')
        end
      }
    end
  else
    Syslog.err(response.body)
    p response.body
  end
}

Syslog.info('end pma');
p "end pma"
FileUtils.rm('/tmp/.pmalock')
