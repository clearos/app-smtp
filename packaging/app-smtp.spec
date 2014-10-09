
Name: app-smtp
Epoch: 1
Version: 2.0.1
Release: 1%{dist}
Summary: SMTP Server
License: GPLv3
Group: ClearOS/Apps
Source: %{name}-%{version}.tar.gz
Buildarch: noarch
Requires: %{name}-core = 1:%{version}-%{release}
Requires: app-base
Requires: app-mail-settings
Requires: app-network
Requires: app-smtp-plugin-core

%description
The SMTP Server provides an incoming and outgoing mail server as well as mail forwarding and SMTP authentication features.

%package core
Summary: SMTP Server - Core
License: LGPLv3
Group: ClearOS/Libraries
Requires: app-base-core
Requires: app-base-core >= 1:1.6.5
Requires: app-certificate-manager-core
Requires: app-events-core
Requires: app-network-core >= 1:1.1.1
Requires: app-mail-core
Requires: cyrus-sasl
Requires: cyrus-sasl-plain
Requires: csplugin-filewatch
Requires: mailx >= 12.4
Requires: postfix >= 2.6.6

%description core
The SMTP Server provides an incoming and outgoing mail server as well as mail forwarding and SMTP authentication features.

This package provides the core API and libraries.

%prep
%setup -q
%build

%install
mkdir -p -m 755 %{buildroot}/usr/clearos/apps/smtp
cp -r * %{buildroot}/usr/clearos/apps/smtp/

install -d -m 0755 %{buildroot}/etc/clearos/smtp.d
install -d -m 0755 %{buildroot}/var/clearos/events/smtp
install -d -m 0755 %{buildroot}/var/clearos/smtp
install -d -m 0755 %{buildroot}/var/clearos/smtp/backup
install -D -m 0644 packaging/authorize %{buildroot}/etc/clearos/smtp.d/authorize
install -D -m 0644 packaging/filewatch-smtp-event.conf %{buildroot}/etc/clearsync.d/filewatch-smtp-event.conf
install -D -m 0644 packaging/postfix.php %{buildroot}/var/clearos/base/daemon/postfix.php

%post
logger -p local6.notice -t installer 'app-smtp - installing'

%post core
logger -p local6.notice -t installer 'app-smtp-core - installing'

if [ $1 -eq 1 ]; then
    [ -x /usr/clearos/apps/smtp/deploy/install ] && /usr/clearos/apps/smtp/deploy/install
fi

[ -x /usr/clearos/apps/smtp/deploy/upgrade ] && /usr/clearos/apps/smtp/deploy/upgrade

exit 0

%preun
if [ $1 -eq 0 ]; then
    logger -p local6.notice -t installer 'app-smtp - uninstalling'
fi

%preun core
if [ $1 -eq 0 ]; then
    logger -p local6.notice -t installer 'app-smtp-core - uninstalling'
    [ -x /usr/clearos/apps/smtp/deploy/uninstall ] && /usr/clearos/apps/smtp/deploy/uninstall
fi

exit 0

%files
%defattr(-,root,root)
/usr/clearos/apps/smtp/controllers
/usr/clearos/apps/smtp/htdocs
/usr/clearos/apps/smtp/views

%files core
%defattr(-,root,root)
%exclude /usr/clearos/apps/smtp/packaging
%dir /usr/clearos/apps/smtp
%dir /etc/clearos/smtp.d
%dir /var/clearos/events/smtp
%dir /var/clearos/smtp
%dir /var/clearos/smtp/backup
/usr/clearos/apps/smtp/deploy
/usr/clearos/apps/smtp/language
/usr/clearos/apps/smtp/libraries
/etc/clearos/smtp.d/authorize
/etc/clearsync.d/filewatch-smtp-event.conf
/var/clearos/base/daemon/postfix.php
