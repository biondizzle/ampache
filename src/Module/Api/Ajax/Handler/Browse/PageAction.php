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

declare(strict_types=0);

namespace Ampache\Module\Api\Ajax\Handler\Browse;

use Ampache\Repository\Model\User;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class PageAction extends AbstractBrowseAction
{
    public function handle(
        ServerRequestInterface $request,
        ResponseInterface $response,
        User $user
    ): array {
        $browse = $this->getBrowse();
        $browse->set_start($_REQUEST['start']);
        ob_start();
        $browse->show_objects(null, $this->getArgument());
        $results[$browse->get_content_div()] = ob_get_clean();

        $browse->store();

        return $results;
    }
}
