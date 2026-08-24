# What this SDK does and does not do

## When to use

Read this before assuming a capability exists as a method on this package. Cloudinary the
platform is much larger than this SDK. Several things agents commonly reach for here are
real products that live elsewhere.

## Start here

Set these up before writing code — they save more time than any snippet on this page:

| Tool | Install | Use for |
|---|---|---|
| Claimable cloud | `npx @cloudinary/cloud` | Credentials in seconds, no signup. See [Get credentials](get-credentials.md). |
| Skills | `npx skills add cloudinary-devs/skills` | Task-oriented instructions for coding agents. |
| MCP servers | [setup guide](https://cloudinary.com/documentation/cloudinary_llm_mcp.md) | Let an agent operate your account directly — search, upload, analyze, configure. |
| CLI | `pipx install cloudinary-cli` | Ad-hoc uploads, bulk operations, and poking at an account without writing code. |

Documentation indexes for agents: [llms.txt](https://cloudinary.com/documentation/llms.txt)
and the full [platform reference](https://cloudinary.com/documentation/cloudinary_references.md).
Any documentation URL returns Markdown if you append `.md`.

## In this package

### Get media in

| To do this | Use | Where to go |
|---|---|---|
| Upload a file, URL, or stream | `$cloudinary->uploadApi()->upload()` | [Upload an image](upload-image.md) |
| Upload a file over ~20 MB | `$cloudinary->uploadApi()->upload()` — chunks automatically | [Upload a large video](upload-large-video.md) |
| Let a browser upload directly | `Cloudinary\Api\ApiUtils::signParameters()` | [Sign a browser upload](sign-browser-upload.md) |
| Upload without a server signature | `$cloudinary->uploadApi()->unsignedUpload()` | [Sign a browser upload](sign-browser-upload.md) |

### Deliver and transform

| To do this | Use | Where to go |
|---|---|---|
| Build a delivery URL | `$cloudinary->image()`, `->video()`, `->raw()` | [Transform an image](transform-and-deliver-image.md) |
| Build an HTML tag | `$cloudinary->imageTag()`, `->videoTag()` | [Transform a video](transform-and-deliver-video.md) |
| Resize, crop, overlay, add effects | `Cloudinary\Transformation\*` | [Transform an image](transform-and-deliver-image.md) |
| Generative fill, replace, and similar AI edits | `Background::generativeFill()`, `Effect::generativeReplace()` | [Transform an image](transform-and-deliver-image.md) |

### Find and manage

| To do this | Use | Where to go |
|---|---|---|
| Search by expression | `$cloudinary->searchApi()` | [Search and manage assets](search-and-manage-assets.md) |
| List, rename, delete, tag | `$cloudinary->adminApi()`, `$cloudinary->uploadApi()` | [Search and manage assets](search-and-manage-assets.md) |
| Find visually similar assets | `$cloudinary->adminApi()->visualSearch()` | [Search and manage assets](search-and-manage-assets.md) |
| Attach structured fields | `$cloudinary->adminApi()->addMetadataField()` | [Use structured metadata](use-structured-metadata.md) |
| Bundle assets into an archive | `$cloudinary->uploadApi()->createArchive()` | [Search and manage assets](search-and-manage-assets.md) |

### Analyze and moderate

| To do this | Use | Where to go |
|---|---|---|
| Run analysis on an asset | `$cloudinary->adminApi()->analyze()` | [Moderate an upload](moderate-upload.md) |
| Queue an upload for moderation | `upload(..., ['moderation' => ...])` | [Moderate an upload](moderate-upload.md) |

Analysis and most moderation kinds are **add-ons**: they must be enabled on your account
before the call succeeds. An unsubscribed add-on fails at request time, not at build time.

### Administer

| To do this | Use |
|---|---|
| Inspect usage and quotas | `$cloudinary->adminApi()->usage()` |
| Manage upload presets, transformations, streaming profiles | `$cloudinary->adminApi()` |
| Manage sub-accounts, users, and access keys | `Cloudinary\Api\Provisioning\AccountApi` |

## Not in this package

These are real Cloudinary capabilities with no method in this SDK. Use the tool named
instead of inventing an API call.

| Capability | Use instead |
|---|---|
| Text-to-image and image-to-video generation | [Image generation APIs](https://cloudinary.com/documentation/image_generation_addon.md) |
| In-browser upload UI | [Upload Widget](https://cloudinary.com/documentation/upload_widget.md) |
| Rendering in a browser or frontend framework | [Frontend SDKs](https://cloudinary.com/documentation/frontend_sdks.md) |
| Multi-step media workflow automation | [MediaFlows](https://cloudinary.com/documentation/mediaflows_user_guide.md) |
| Interactive agent-driven asset operations | [MCP servers and Skills](https://cloudinary.com/documentation/cloudinary_llm_mcp.md) |
| Browsing and organizing assets by hand | [Media Library](https://cloudinary.com/documentation/digital_asset_management_overview.md) in the Console |
| Laravel-native integration | [`cloudinary-labs/cloudinary-laravel`](https://github.com/cloudinary-labs/cloudinary-laravel) |
| WordPress, Magento, and similar platforms | [Platform integrations](https://cloudinary.com/documentation/integrations.md) |

## Troubleshooting

| Symptom | Cause |
|---|---|
| `Call to undefined method` | The capability is on the platform but not in this SDK. Check the table above. |
| `AuthorizationRequired` on an analysis or moderation call | The add-on is not enabled for your account. |
| `RateLimited` when you are not sending many requests | Unsubscribed add-ons can surface as rate-limit errors rather than permission errors. |

## Related

- [Import and call the SDK](import-and-call.md)
- [Get Cloudinary credentials](get-credentials.md)
- [Troubleshoot errors](troubleshoot-errors.md)
