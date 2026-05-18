# Playwright Artifacts

- Never leave files produced by the Playwright MCP plugin in the project — screenshots, page/network snapshots, console-log dumps, and any ad-hoc preview HTML written to verify rendering must all be deleted before the task ends.
- The plugin's `.playwright-mcp/` working directory accumulates session output (`page-*.yml`, `console-*.log`, `page-*.png`); either delete its contents at the end of the session or ensure the directory is git-ignored so the files cannot be accidentally committed.
