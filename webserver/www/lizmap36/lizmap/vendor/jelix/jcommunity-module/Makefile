
VERSION=$(shell cat VERSION)


build:
	zip -r jcommunity.zip modules/ VERSION INSTALL.md README.md LICENCE composer.json

.PHONY: deploy
deploy: build
	scp jcommunity.zip $(JELIX_ORG_DEPLOY_SSH):$(JCOMMUNITY_JELIX_ORG_DEPLOY_DIR)/jcommunity_$(VERSION).zip
