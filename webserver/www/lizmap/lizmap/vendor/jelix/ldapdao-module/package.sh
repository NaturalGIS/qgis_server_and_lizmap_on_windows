#!/usr/bin/env bash

VERSION=$(cat VERSION)

zip -r ldapdao-module-$VERSION.zip ldapdao/ CHANGELOG.md LICENCE README.md VERSION

