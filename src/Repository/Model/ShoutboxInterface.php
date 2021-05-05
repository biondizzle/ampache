<?php
/*
 * vim:set softtabstop=4 shiftwidth=4 expandtab:
 *
 * LICENSE: GNU Affero General Public License, version 3 (AGPL-3.0-or-later)
 * Copyright 2001 - 2020 Ampache.org
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace Ampache\Repository\Model;

interface ShoutboxInterface
{
    public function getId(): int;

    public function isNew(): bool;

    public function getObjectType(): string;

    public function getObjectId(): int;

    public function getUserId(): int;

    public function getSticky(): int;

    public function getText(): string;

    public function getData(): string;

    public function getDate(): int;

    public function getStickyFormatted(): string;

    public function getTextFormatted(): string;

    public function getDateFormatted(): string;
}
