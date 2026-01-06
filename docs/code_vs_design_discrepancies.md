# Code Implementation vs Design Discrepancies

This document outlines the discrepancies found between the project documentation/UML designs and the current codebase implementation.

## 1. Dividends Distribution (Critical)
**Status:** **Missing Implementation**

*   **Requirement:** Both `project.md` and `dividendsDistribution.wsd` specify a scheduled job (e.g., nightly) to calculate and distribute dividends to shareholders based on meme performance.
*   **Current Code:**
    *   Database infrastructure exists (`dividend_histories` table, `Transaction` type `dividend`).
    *   **Missing Logic:** No Service, Job, or Console Command exists to actually calculate or execute the dividend distribution. The `app/Console/Commands` directory is missing.

## 2. Registration - Password Security
**Status:** **Incomplete Validation**

*   **Requirement:** `registration.wsd` and `project.md` require a "Strong Password" policy: minimum 8 characters, at least one **Uppercase** letter, one **Number**, and one **Symbol**.
*   **Current Code:** `App\Http\Requests\RegisterRequest` only enforces:
    *   Minimum 8 characters
    *   At least one number
*   **Missing:** Validation rules for Uppercase letters (`mixedCase()`) and Symbols (`symbols()`) are missing.

## 3. Meme Lifecycle - Rejection Mechanics
**Status:** **Implementation Mismatch**

*   **Requirement:** `memesLifecycle.wsd` specifies that when an Admin rejects a meme:
    1.  Status should be updated to `REJECTED`.
    2.  The record should be **Soft Deleted**.
*   **Current Code:** `AdminService::rejectMeme` only updates the status to `suspended`.
    *   It uses `suspended` instead of `REJECTED` (minor terminology mismatch).
    *   It does **not** perform a soft delete on the record.
