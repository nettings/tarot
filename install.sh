#!/bin/bash

THISPATH=$(realpath $(dirname $0))

function parse_config {
	php -r "require('${THISPATH}/web/includes/globals.php'); print($1);"
}

function install_services {
	SYSTEMD_SERVICE_PATH="${DESTDIR}${PREFIX}/lib/systemd/system"
	for i in "$THISPATH"/systemd/*.template ; do
		SERVICE=$(basename "$i" | sed 's/\.template$//')
		TARGET="$SYSTEMD_SERVICE_PATH"/"$SERVICE"
		echo -n "Installing $SERVICE to $SYSTEMD_SERVICE_PATH..."
		php -r "
			require('${THISPATH}/web/includes/globals.php');
			include('${i}');
"			> "$TARGET" && echo "succeeded." || echo "failed."
	done
}


function install_webapp {
	echo cp -av "$THISPATH"/web "$APPROOT"
}

function create_statedir {
	mkdir "$STATE_PATH"
	chown -R "$WEBUSER":"$WEBGROUP" "$STATE_PATH"
}

function install_zeroimg {
	echo ln -s /dev/zero "$IMAGE_PATH"
}

PREFIX=${PREFIX:-$(parse_config PREFIX)}
DESTDIR=${DESTDIR:-$(parse_config DESTDIR)}

PROGNAME=${PROGNAME:-$(parse_config PROGNAME)}
APPROOT=${APPROOT:-$(parse_config APPROOT)}
STATE_PATH=${STATE_PATH:-$(parse_config STATE_PATH)}
IMAGE_PATH=${IMAGE_PATH:-$(parse_config IMAGE_PATH)}
WEBUSER=${WEBUSER:-$(parse_config WEBUSER)}
WEBGROUP=${WEBGROUP:-$(parse_config WEBGROUP)}

cat << EOF
$PROGNAME installer

Installation prefix:   '$PREFIX'  (override by setting \$PREFIX)
Destination directory: '$DESTDIR' (override by setting \$DESTDIR)

Configuration (set in $THISPATH/web/include/config.php):
Web Application root:  '$APPROOT'
State file directory:  '$STATE_PATH'
Image directory:       '$IMAGE_PATH'
EOF

install_services
install_zeroimg
install_webapp
create_statedir

systemctl enable tarot.path
systemctl restart tarot.path

