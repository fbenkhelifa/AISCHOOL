# AI School Website — Design Brief

## Objective

Build a multi-agent educational AI assistant grounded in curated learning materials and optimized for answer quality, pedagogical clarity, and traceability.

## Target users

- Students seeking concept explanations and guided problem solving
- Instructors needing consistent assistant behavior over course content

## Core design principles

1. Grounding-first responses via retrieval over trusted educational sources
2. Agent specialization for reliability and maintainability
3. Evaluation-aware generation with explicit quality checks
4. Explainability through citations and structured reasoning traces

## Agent roles

- **Intake Agent**: interprets user intent, level, and constraints
- **Retriever Agent**: retrieves relevant context from vector + document stores
- **Tutor Agent**: composes pedagogically structured response
- **Evaluator Agent**: scores faithfulness/relevance and enforces quality gates
- **Citation Agent**: attaches sources to final output

## Data and knowledge layer

- Curated course documents, lecture notes, and practice materials
- Versioned ingestion/indexing for reproducibility
- Retrieval strategy: hybrid semantic + keyword search

## Delivery milestones

1. Documentation-first scaffold
2. Baseline RAG + orchestration prototype
3. Evaluation framework integration
4. MVP demo and deployment packaging
