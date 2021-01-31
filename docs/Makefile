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
	curl -O https://cloudinary-res.cloudinary.com/raw/upload/docsite/sami.phar

clean:
	@rm -rf $(CACHE_DIR) $(BUILD_DIR)
