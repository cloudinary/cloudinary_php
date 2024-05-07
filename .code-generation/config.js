module.exports = {
    "SDKSpecVersion": {},
    "langConfig": {
        newInstanceSyntax: '(new #name(#req))#optional',
        lang: 'PHP',
        methodDelimiter: '->',
        groupDelimiter: '::',
        openQualifiersChar: '',
        closeQualifiersChar: '',
        closeTransformationChar: '',
        unsupportedTxParams: [],
        unsupportedSyntaxList: [],
        mainTransformationString: {
            openSyntaxString: {
                image: '(new ImageTag(\'#publicID\'))',
                video: '(new VideoTag(\'#publicID\'))',
                media: '(new Media(\'#publicID\'))'
            },
            openUrlSyntaxString: {
                image: '(new Image(\'#publicID\'))',
                video: '(new Video(\'#publicID\'))',
                media: '(new Media(\'#publicID\'))'
            },
            closeSyntaxString: ';'
        },
        openActionChar: '(',
        closeActionChar: ')',
        hideActionGroups: false,
        overwritePreset: 'php',
        arraySeparator: ', ',
        arrayOpen: '[',
        arrayClose: ']',
        formats: {
            formatMethod: 'camelCase',
            formatClassOrEnum: 'PascalCase',
            formatFloat: (f) => {
                if (!f.toString().includes('.')) {
                    return `${f}.0` // In JS world, 1.0 is 1, so we make sure 1.0 stays 1.0
                } else {
                    return f;
                }
            }
        },
        methodNameMap: {
            'signature': 'sign',
            'url_suffix': 'suffix'
        },
        classNameMap: {},
        childTransformations: {
            image: {
                open: "(new ImageTransformation())", // TODO Seems like we should reuse the newInstanceSyntax
                close: '',
            },
            video: {
                open: "(new VideoTransformation())",// TODO Seems like we should reuse the newInstanceSyntax
                close: '',
            },
            media: {
                open: "(new Transformation())",// TODO Seems like we should reuse the newInstanceSyntax
                close: '',
            }
        },
        importStatementsTemplate: {
            action: 'use Cloudinary\\Transformation\\<GROUP>;',
            qualifier: 'use Cloudinary\\Transformation\\<GROUP>;',
            importCase: "PascalCase",
        },
    },
    "overwrites": {
        "qualifiers": {
            color_override: (payload) => {
                const {qualifierDTO, langConfig} = payload;
                const colorName = qualifierDTO.qualifiers[0].name;
                const group = qualifierDTO.qualifiers[0].group;

                // TODO this should be streamlined with how we de. al with color.
                return `->colorOverride(Color::${colorName.toUpperCase()})`
            }
        }
    }
}
