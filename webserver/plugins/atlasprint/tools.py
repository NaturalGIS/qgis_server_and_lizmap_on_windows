__copyright__ = 'Copyright 2021, 3Liz'
__license__ = 'GPL version 3'
__email__ = 'info@3liz.org'

import configparser

from pathlib import Path
from typing import Dict, Tuple

from qgis.core import Qgis, QgsMessageLog

from atlasprint.logger import Logger


def version() -> str:
    """ Returns the Lizmap current version. """
    file_path = Path(__file__).parent.joinpath('metadata.txt')
    config = configparser.ConfigParser()
    try:
        config.read(file_path, encoding='utf8')
    except UnicodeDecodeError:
        # Issue LWC https://github.com/3liz/lizmap-web-client/issues/1908
        # Maybe a locale issue ?
        # Do not use logger here, circular import
        # noinspection PyTypeChecker
        QgsMessageLog.logMessage(
            "Error, an UnicodeDecodeError occurred while reading the metadata.txt. Is the locale "
            "correctly set on the server ?",
            "AtlasPrint", Qgis.Critical)
        return 'NULL'
    else:
        return config["general"]["version"]


def get_lizmap_groups(params: Dict[str, str], headers: Dict[str, str]) -> Tuple[str]:
    """ Get Lizmap user groups provided by the request

    COPIED from Lizmap plugin, server side in core.py
    Only the signature is simplified.
    This code is tested in the Lizmap plugin.
    """

    # Defined groups
    groups = []
    logger = Logger()

    # Get Lizmap User Groups in request headers
    if headers:
        logger.info("Request headers provided")
        # Get Lizmap user groups defined in request headers
        user_groups = headers.get('X-Lizmap-User-Groups')
        if user_groups is not None:
            groups = [g.strip() for g in user_groups.split(',')]
            logger.info("Lizmap user groups in request headers : {}".format(','.join(groups)))
    else:
        logger.info("No request headers provided")

    if len(groups) != 0:
        # noinspection PyTypeChecker
        return tuple(groups)

    logger.info("No lizmap user groups in request headers")

    # Get group in parameters
    if params:
        # Get Lizmap user groups defined in parameters
        user_groups = params.get('LIZMAP_USER_GROUPS')
        if user_groups is not None:
            groups = [g.strip() for g in user_groups.split(',')]
            logger.info("Lizmap user groups in parameters : {}".format(','.join(groups)))

    # noinspection PyTypeChecker
    return tuple(groups)


def get_lizmap_user_login(params: Dict[str, str], headers: Dict[str, str]) -> str:
    """ Get Lizmap user login provided by the request

    COPIED from Lizmap plugin, server side in core.py
    Only the signature is simplified.
    This code is tested in the Lizmap plugin.
    """
    # Defined login
    login = ''

    logger = Logger()

    # Get Lizmap User Login in request headers
    if headers:
        logger.info("Request headers provided")
        # Get Lizmap user login defined in request headers
        user_login = headers.get('X-Lizmap-User')
        if user_login is not None:
            login = user_login
            logger.info("Lizmap user login in request headers : {}".format(login))
    else:
        logger.info("No request headers provided")

    if login:
        return login

    logger.info("No lizmap user login in request headers")

    # Get login in parameters
    if params:
        # Get Lizmap user login defined in parameters
        user_login = params.get('LIZMAP_USER')
        if user_login is not None:
            login = user_login
            logger.info("Lizmap user login in parameters : {}".format(login))

    return login
