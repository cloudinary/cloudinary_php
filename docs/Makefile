CACHE_DIR = cache
BUILD_DIR = build

DOCS_TOOL = sami.phar

PHP?=php

CONFIG=sami_config.php

.PHONY: all clean update

all: update

update: $(DOCS_TOOL)
	$(PHP) $(DOCS_TOOL) update $(CONFIG)

$(DOCS_TOOL):
	curl -O http://get.sensiolabs.org/sami.phar

clean:
	@rm -rf $(CACHE_DIR) $(BUILD_DIR)
