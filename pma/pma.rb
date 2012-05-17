
require 'rubygems'

require 'net/http'
require 'json'
require 'logger'
require 'syslog'
require 'fileutils'

def shellesc(str)
  str.gsub!(/[\!\"\$\&\'\(\)\*\,\:\;\<\=\>\?\[\\\]\^\`\{\|\}\t ]/, '\\\\\\&')
  str
end

log = Logger.new(STDOUT)

Syslog.open()
Syslog.info('start pma')
 log.info("start pma")
if File.exist?("/tmp/.pmalock") then
  Syslog.info('still run ')
  log.warn("still run")
  Syslog.info('exit pma')
  log.info("exit pma")
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
    log.info("server detail")
  
    if details != [] then
      expectedDomains = details['domain']
  
      installTargetDomains = expectedDomains - installDomains
      log.info(installTargetDomains)
      installTargetDomains.each { |domain|
        domain = shellesc(domain)
        log.info("install " + domain)
        req = Net::HTTP::Get.new('/api/sns/detail?domain='+domain)
        snsResponse = http.request(req)
        log.info("sns detail")
        p snsResponse.body
        if snsResponse.code == '200' then
          snsDetail = JSON.parse(snsResponse.body)
          adminEmail = shellesc(snsDetail['adminEmail'])
          log.debug("domain :"  + domain)
          log.debug("email :" + adminEmail)
          userResult = ""
          adminResult = ""
          IO.popen('/opt/sabakan/autoinst/install.sh '+domain+' '+adminEmail) do |io|
            while line = io.gets
              userResult = line.split(" ")[0]
              adminResult = line.split(" ")[1]
            end
            if userResult == nil || adminResult == nil then
              log.error("fail to install " + domain)
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
          log.error("fail to install " + domain)
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
          log.info("sns delete")
        else
          log.error("fail to delete " + domain)

          http.request_post('/api/server/event', 'eventType=error&message=deletefailed')
        end
      }
    end
  else
    Syslog.err(response.body)
    log.error(response.body)
  end
}

Syslog.info('end pma');
log.info("end pma")
FileUtils.rm('/tmp/.pmalock')
