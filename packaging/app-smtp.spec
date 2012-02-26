
Name: app-smtp
Epoch: 1
Version: 1.0.5
Release: 1%{dist}
Summary: SMTP Server
License: GPLv3
Group: ClearOS/Apps
Source: %{name}-%{version}.tar.gz
Buildarch: noarch
Requires: %{name}-core = 1:%{version}-%{release}
Requires: app-base

%description
SMTP Server description...

%package core
Summary: SMTP Server - APIs and install
License: LGPLv3
Group: ClearOS/Libraries
Requires: app-base-core
Requires: app-network-core
Requires: cyrus-sasl-plain
Requires: mailx >= 12.4
Requires: php-pear-Net-SMTP
Requires: postfix >= 2.6.6

%description core
SMTP Server description...

This package provides the core API and libraries.

%prep
%setup -q
%build

%install
mkdir -p -m 755 %{buildroot}/usr/clearos/apps/smtp
cp -r * %{buildroot}/usr/clearos/apps/smtp/

install -d -m 0755 %{buildroot}/var/clearos/smtp
install -d -m 0755 %{buildroot}/var/clearos/smtp/backup
install -D -m 0755 packaging/mailprefilter %{buildroot}/usr/sbin/mailprefilter
install -D -m 0644 packaging/postfix-ldap-aliases.cf %{buildroot}/var/clearos/ldap/synchronize/postfix-ldap-aliases.cf
install -D -m 0644 packaging/postfix-ldap-groups.cf %{buildroot}/var/clearos/ldap/synchronize/postfix-ldap-groups.cf
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
%exclude /usr/clearos/apps/smtp/tests
%dir /usr/clearos/apps/smtp
%dir /var/clearos/smtp
%dir /var/clearos/smtp/backup
/usr/clearos/apps/smtp/deploy
/usr/clearos/apps/smtp/language
/usr/clearos/apps/smtp/libraries
/usr/sbin/mailprefilter
/var/clearos/ldap/synchronize/postfix-ldap-aliases.cf
/var/clearos/ldap/synchronize/postfix-ldap-groups.cf
/var/clearos/base/daemon/postfix.php
