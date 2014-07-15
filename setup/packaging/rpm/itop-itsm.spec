%define webconfdir %{?_webconfdir}%{!?_webconfdir:%{_sysconfdir}/httpd}
%define logdir %{?_logdir}%{!?_logdir:%{_var}/log}

Name: itop-itsm
Version: 2.0.3
Release: 1%{?dist}
# TODO: Use a variable below
Summary: iTop: IT Operational Portal
# TODO: Use a variable below
Group: Applications/Databases 
License: AGPLv3+
URL: http://www.combodo.com/itop
#TODO Adjust the line below to the actual name of the "upstream" zip package
Source0: iTop-2.0.3-1916.zip
#Source4: install.sh
BuildArch: noarch
BuildRoot: %{_tmppath}/%{name}-%{version}-%{release}-root-%(%{__id_u} -n)

# TODO: Use a variable below
Requires: php >= 5.2.0, php-mysql, php-mcrypt, php-xml, php-cli, php-soap, php-ldap, graphviz
#, php-pecl-apc
# TODO: Use a variable below
BuildRequires: unzip

# TODO: Use a variable below
%description
iTop is an open source CMDB.

%prep
%setup -c %{name}
#cp %SOURCE4 ./web/setup/install/

%build

%install
rm -rf %{buildroot}

export _ITOP_NAME_=%{name}
export _ITOP_SYSCONFDIR_=%{_sysconfdir}
export _ITOP_WEBCONFDIR_=%{webconfdir}
export _ITOP_VARDIR_=%{_var}
export PREFIX=%{_prefix}
export HEAD=%{buildroot}
chmod 755 ./web/setup/install/install.sh
./web/setup/install/install.sh

%clean
rm -rf %{buildroot}

%files
%defattr(-,root,root,-)
#%dir %{_datadir}/%{name}
%dir %{_var}/lib/%{name}
%{_datadir}/*
%{webconfdir}/conf.d/%{name}.conf
%{_sysconfdir}/cron.d/%{name}
%{_sysconfdir}/logrotate.d/%{name}
%{_var}/lib/%{name}/approot.inc.php

# TODO: Use a variable below
%defattr(-,apache,root,-)
%dir %{_sysconfdir}/%{name}
#%config(noreplace) %{_sysconfdir}/%{name}/production/cron.params
%dir %{_sysconfdir}/%{name}/test
%dir %{_sysconfdir}/%{name}/production
%dir %{_sysconfdir}/%{name}/toolkit
%dir %{logdir}/%{name}
%dir %{_var}/lib/%{name}/env-production
%dir %{_var}/lib/%{name}/env-test
%dir %{_var}/lib/%{name}/env-toolkit
%dir %{_var}/lib/%{name}/data


%changelog
* Tue Jul 15 2014 Denis Flaven <denis.flaven@combodo.com>
- Added use of logrotate for cron.log and error.log, thanks to Igor Gnatenko
- Fixed the line endings on README and LICENSE
* Mon Jul 14 2014 Denis Flaven <denis.flaven@combodo.com>
- Support of Apache 2.4
- Packaging of iTop 2.0.3
* Mon Aug 05 2013 Denis Flaven <denis.flaven@combodo.com>
- ver 1.0
