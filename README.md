# AI School Website Project (Artifact Repository)

![Status](https://img.shields.io/badge/status-artifact%20repo-orange)
![License](https://img.shields.io/badge/license-MIT-green)

## What is this

This repository currently stores packaged coursework artifacts for an AI school web project: a source archive (`AISCHOOL.zip`) and a project report PDF. It exists as a historical/project-delivery snapshot rather than a fully version-controlled source repository.

## Why it exists

The repository preserves project deliverables and documentation in their submitted form. It is useful for reference, but not ideal for collaborative development until the source archive is extracted into regular tracked folders.

## Architecture / Stack

Current evidence in this repository indicates a web-application setup requiring a local web stack (PHP/MySQL) after extraction of `AISCHOOL.zip`.

```text
User Browser -> Local Web Server -> Application Source (inside ZIP) -> Database
```

## Installation

### 1) Clone repository

```bash
git clone https://github.com/fbenkhelifa/ai-school-website.git
cd ai-school-website
```

### 2) Extract source package

```text
Extract AISCHOOL.zip into a local working directory.
```

### 3) Configure local runtime

- Start local web stack (Apache/Nginx + PHP + MySQL)
- Point web root to extracted project directory

### 4) Use report as technical reference

- Open `AI School Website Project Report.pdf` for documented requirements and implementation notes

## Usage

- Launch the extracted web app in your local server environment
- Follow the report’s workflow descriptions for functional validation

## Project structure

```text
ai-school-website/
├── AISCHOOL.zip                         # Packaged source archive
├── AI School Website Project Report.pdf # Project report
├── README.md
├── .gitignore
└── LICENSE
```

## Limitations

- Source code is not directly versioned in the repository (stored as ZIP).
- File-level diffs, code review, and collaborative pull-request workflows are limited.
- Security and quality checks cannot be comprehensively automated on archived source.

## Roadmap

1. Extract `AISCHOOL.zip` and commit source folders/files directly.
2. Introduce standard project layout (`src`, `public`, `config`, `docs`).
3. Add dependency/bootstrapping documentation and CI checks.
4. Migrate this concept into `alschool` for a production-grade multi-agent RAG platform narrative.

## License

Licensed under MIT. See [`LICENSE`](./LICENSE).
